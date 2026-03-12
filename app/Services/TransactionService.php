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
     * Orchestrates the full transfer workflow:
     * Step 1 – Fetch FX quote from Revolut
     * Step 2 – Create transaction record (pending)
     * Step 3 – Execute exchange (CHF→INR)
     * Step 4 – Send payment to recipient
     * Step 5 – Compliance check
     */
    public function initiateTransfer(array $data): Transaction
    {
        $agentId    = Auth::id();
        $chfAmount  = (float) $data['chf_amount'];

        // Step 0: Check wallet balance
        $wallet = $this->wallet->getForAgent($agentId);
        if ($wallet->chf_balance < $chfAmount) {
            throw new \Exception('Insufficient wallet balance. Please top up your wallet.');
        }

        // Step 1: Fetch and store FX quote
        $quote = $this->fxService->fetchAndStoreQuote($chfAmount, $agentId, $data['target_currency'] ?? 'INR');

        // Step 2: Create transaction as pending
        $transaction = $this->transactions->create([
            'agent_id'         => $agentId,
            'customer_id'      => $data['customer_id'],
            'recipient_id'     => $data['recipient_id'],
            'fx_quote_id'      => $quote->id,
            'target_currency'  => $quote->to_currency,
            'chf_amount'       => $chfAmount, // Total Paid
            'send_amount'      => $quote->send_amount,
            'target_amount'    => $quote->target_amount,
            'commission'       => $quote->fee,
            'agent_commission' => $quote->agent_commission,
            'admin_commission' => $quote->admin_commission,
            'rate'             => $quote->rate,
            'status'           => 'pending',
            'notes'            => $data['notes'] ?? null,
        ]);

        // Step 2.5: Deduct from wallet
        $this->wallet->debitForTransfer($agentId, $chfAmount, $transaction);

        // Step 3 & 4: Execute payment via Revolut
        try {
            $this->transactions->update($transaction->id, ['status' => 'processing']);

            $recipient = $transaction->recipient;
            $paymentRef = $this->revolutPayment->sendPayment([
                'amount'          => $quote->target_amount,
                'currency'        => $quote->to_currency,
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
            $transaction->refresh();
            if ($transaction->agent_commission > 0) {
                $this->wallet->creditCommission($transaction->agent_id, $transaction->agent_commission, $transaction);
            }

            // Notify agent
            Auth::user()->notify(new TransactionCompleted($transaction->fresh()));

        } catch (\Exception $e) {
            $this->transactions->update($transaction->id, [
                'status' => 'failed',
                'notes'  => $e->getMessage(),
            ]);

            Auth::user()->notify(new TransactionFailed($transaction->fresh()));
        }

        // Step 5: Compliance check
        $fresh = $transaction->fresh();
        $this->compliance->checkAndFlag($fresh);

        return $fresh;
    }

    public function updateStatus(int $id, string $status)
    {
        return $this->transactions->update($id, ['status' => $status]);
    }
}
