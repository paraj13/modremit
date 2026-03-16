<?php

namespace App\Services;

use App\Models\Transaction;
use App\Notifications\TransactionCompleted;
use App\Notifications\TransactionFailed;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactions,
        private FxService $fxService,
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

            // Step 3: Execute actual payment via Wise
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

            // Trigger actual Wise Payment
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
     * Executes the actual payment via Wise AFTER compliance approval.
     */
    public function executePayment(int $transactionId): bool
    {
        $transaction = $this->transactions->findById($transactionId);
        if (!$transaction || $transaction->status !== 'processing') {
            return false;
        }

        try {
            /** @var \App\Integrations\Wise\WiseTransferService $wise */
            $wise = app(\App\Integrations\Wise\WiseTransferService::class);
            
            $recipient = $transaction->recipient;
            
            // Bypass the actual Wise API payment execution via user request for now
            // $result = $wise->send([
            //     'chf_amount'      => $transaction->chf_amount,
            //     'target_currency' => $transaction->target_currency,
            //     'recipient_name'  => $recipient->name,
            //     'account_number'  => $recipient->account_number,
            //     'iban'            => $recipient->iban,
            //     'swift_code'      => $recipient->swift_code,
            //     'routing_number'  => $recipient->routing_number,
            //     'ifsc_code'       => $recipient->ifsc_code,
            //     'upi_id'          => $recipient->upi_id,
            //     'sort_code'       => $recipient->sort_code,
            //     'address_line_1'  => $recipient->address_line_1,
            //     'city'            => $recipient->city,
            //     'postal_code'     => $recipient->postal_code,
            //     'state'           => $recipient->state,
            //     'country'         => $recipient->country,
            //     'reference'       => 'TXN-' . $transaction->id,
            // ]);

            $result = \App\Integrations\Wise\WiseTransferResult::dummy([
                'target_currency' => $transaction->target_currency,
            ]);

            $this->transactions->update($transaction->id, [
                'status'           => $result->status === 'completed' ? 'completed' : 'processing',
                'wise_transfer_id' => $result->transferId,
                'wise_quote_id'    => $result->quoteId,
                'wise_status'      => $result->status,
                'wise_response'    => $result->rawResponse,
                'payment_ref'      => $result->transferId, // Generic ref for backward compat
            ]);

            // If it completed immediately, handle commission and notification
            if ($result->status === 'completed') {
                $this->handleCompletion($transaction->fresh());
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Wise Payment Execution Failed', [
                'transaction_id' => $transactionId,
                'error'          => $e->getMessage()
            ]);

            $this->transactions->update($transaction->id, [
                'status'         => 'failed',
                'failure_reason' => $e->getMessage(),
                'wise_response'  => ['error' => $e->getMessage()],
            ]);

            // REFUND Wallet on failure
            $this->wallet->refundForFailedTransfer($transaction->agent_id, (float)$transaction->chf_amount, $transaction);

            $transaction->agent->notify(new TransactionFailed($transaction->fresh()));
            return false;
        }
    }

    private function handleCompletion(Transaction $transaction): void
    {
        // Credit commission to agent wallet
        if ($transaction->agent_commission > 0) {
            $this->wallet->creditCommission($transaction->agent_id, $transaction->agent_commission, $transaction);
        }

        // Notify agent
        // $transaction->agent->notify(new TransactionCompleted($transaction));
    }

    public function updateStatus(int $id, string $status)
    {
        return $this->transactions->update($id, ['status' => $status]);
    }
}
