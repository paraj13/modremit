<?php

namespace App\Services;

use App\Models\Transaction;
use App\Notifications\TransactionCompleted;
use App\Notifications\TransactionFailed;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Integrations\Revolut\RevolutFxService;
use App\Integrations\Revolut\RevolutPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactions,
        private FxService $fxService,
        private RevolutFxService $revolutFx,
        private RevolutPaymentService $revolutPayment,
        private ComplianceService $compliance,
        private WalletService $wallet
    ) {}

    public function listForAgent(array $filters = [])
    {
        return $this->transactions->allForAgent(Auth::id(), $filters);
    }

    public function listAll(array $filters = [])
    {
        return $this->transactions->all($filters);
    }

    public function find(int $id)
    {
        return $this->transactions->findById($id);
    }

    public function getStats(array $params = []): array
    {
        return $this->transactions->getStats($params);
    }

    public function getStatsForAgent(int $agentId, array $params = []): array
    {
        return $this->transactions->getStatsForAgent($agentId, $params);
    }

    public function getFlagged()
    {
        return $this->transactions->getFlagged();
    }

    /**
     * Orchestrates the initiation of a transfer:
     * Step 0: Validate balance
     * Step 1: Resolve FX Quote
     * Step 2: Create transaction as 'pending_compliance'
     * Step 3: Create compliance log
     * NOTE: Wallet deduction happens only after admin approval now.
     */
    public function initiateTransfer(array $data): Transaction
    {
        $agentId    = Auth::id();
        $chfAmount  = (float) $data['chf_amount'];

        // Step 0: Check wallet balance BEFORE any persistence
        $wallet = $this->wallet->getForAgent($agentId);
        if ($wallet->chf_balance < $chfAmount) {
            throw new \App\Exceptions\InsufficientWalletBalanceException();
        }

        // Step 1: Resolve FX quote
        $quote = null;
        if (!empty($data['quote_id'])) {
            $quote = $this->fxService->findById($data['quote_id']);
            if (!$quote || 
                $quote->expires_at->isPast() || 
                $quote->to_currency !== ($data['target_currency'] ?? 'INR') ||
                abs($quote->chf_amount - $chfAmount) > 0.01) 
            {
                $quote = null;
            }
        }

        if (!$quote) {
            $quote = $this->fxService->fetchAndStoreQuote($chfAmount, $agentId, $data['target_currency'] ?? 'INR');
        }

        return DB::transaction(function() use ($agentId, $chfAmount, $quote, $data) {
            // Step 2: Create transaction as processing
            $transaction = $this->transactions->create([
                'agent_id'         => $agentId,
                'customer_id'      => $data['customer_id'],
                'recipient_id'     => $data['recipient_id'],
                'fx_quote_id'      => $quote->id,
                'target_currency'  => $quote->to_currency,
                'chf_amount'       => $chfAmount,
                'send_amount'      => $quote->send_amount,
                'target_amount'    => $quote->target_amount,
                'commission'       => $quote->fee,
                'agent_commission' => $quote->agent_commission,
                'admin_commission' => $quote->admin_commission,
                'rate'             => $quote->rate,
                'status'           => 'processing',
                'notes'            => $data['notes'] ?? null,
            ]);

            // Deduct from wallet immediately
            $this->wallet->debitForTransfer($agentId, $chfAmount, $transaction);

            // Step 3: Execute actual payment via Revolut (which updates status tracking & handles commission)
            $this->executePayment($transaction->id);
            
            // Refresh transaction after execution to get latest status
            $transaction = $transaction->fresh();

            // Step 4: Compliance check & flag (strictly for monitoring purposes now)
            $this->compliance->checkAndFlag($transaction);

            // If not flagged for a specific reason, create a generic compliance log for review
            if (!$transaction->flagged) {
                $this->compliance->flagTransaction($transaction, "Routine compliance record.");
            }

            return $transaction;
        });
    }

    /**
     * Admin explicitly approves transaction.
     * Deducts wallet, upgrades status, and executes payment.
     */
    public function approveTransaction(int $transactionId): bool
    {
        $transaction = $this->transactions->findById($transactionId);
        if (!$transaction || $transaction->status !== 'pending_compliance') {
            return false;
        }

        // Check wallet balance one last time before execution
        $wallet = $this->wallet->getForAgent($transaction->agent_id);
        if ($wallet->chf_balance < $transaction->chf_amount) {
            $this->rejectTransaction($transactionId, "Insufficient agent wallet balance at time of execution.");
            return false;
        }

        return DB::transaction(function() use ($transaction) {
            // Deduct from wallet now
            $this->wallet->debitForTransfer($transaction->agent_id, (float) $transaction->chf_amount, $transaction);

            // Update status to processing
            $this->updateStatus($transaction->id, 'processing');

            // Trigger actual Revolut Payment
            return $this->executePayment($transaction->id);
        });
    }

    /**
     * Admin explicitly rejects transaction.
     * No wallet deduction occurs.
     */
    public function rejectTransaction(int $transactionId, string $notes): bool
    {
        $transaction = $this->transactions->findById($transactionId);
        if (!$transaction || $transaction->status !== 'pending_compliance') {
            return false;
        }

        $this->transactions->update($transaction->id, [
            'status' => 'rejected',
            'notes'  => $notes,
        ]);

        $transaction->agent->notify(new TransactionFailed($transaction->fresh()));
        return true;
    }

    /**
     * Executes the actual payment via Revolut AFTER compliance approval.
     */
    public function executePayment(int $transactionId): bool
    {
        $transaction = $this->transactions->findById($transactionId);
        if (!$transaction || $transaction->status !== 'processing') {
            return false;
        }

        try {
            $recipient = $transaction->recipient;
            $paymentRef = $this->revolutPayment->sendPayment([
                'amount'          => $transaction->target_amount,
                'currency'        => $transaction->target_currency,
                'recipient_name'  => $recipient->name,
                'account_number'  => $recipient->account_number,
                'iban'            => $recipient->iban,
                'swift_code'      => $recipient->swift_code,
                'routing_number'  => $recipient->routing_number,
                'sort_code'       => $recipient->sort_code,
                'ifsc_code'       => $recipient->ifsc_code,
                'upi_id'          => $recipient->upi_id,
                'reference'       => 'TXN-' . $transaction->id,
            ]);

            $this->transactions->update($transaction->id, [
                'status'       => 'completed',
                'payment_ref'  => $paymentRef,
            ]);

            // Credit commission to agent wallet
            if ($transaction->agent_commission > 0) {
                $this->wallet->creditCommission($transaction->agent_id, $transaction->agent_commission, $transaction);
            }

            // Notify agent
            $transaction->agent->notify(new TransactionCompleted($transaction->fresh()));

            return true;
        } catch (\Exception $e) {
            $this->transactions->update($transaction->id, [
                'status' => 'failed',
                'notes'  => $e->getMessage(),
            ]);

            $transaction->agent->notify(new TransactionFailed($transaction->fresh()));
            return false;
        }
    }

    public function updateStatus(int $id, string $status)
    {
        return $this->transactions->update($id, ['status' => $status]);
    }
}
