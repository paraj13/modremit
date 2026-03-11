<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\Contracts\ComplianceRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ComplianceService
{
    public function __construct(
        private ComplianceRepositoryInterface $compliance
    ) {}

    public function checkAndFlag(Transaction $transaction): void
    {
        $threshold = (float) config('remittance.large_transfer_threshold', 5000);
        $reasons = [];

        if ($transaction->chf_amount >= $threshold) {
            $reasons[] = "Large transfer: CHF {$transaction->chf_amount} (threshold: CHF {$threshold})";
        }

        // Additional fraud signals could be added here
        if (!empty($reasons)) {
            $this->flagTransaction($transaction, implode('; ', $reasons));
        }
    }

    public function flagTransaction(Transaction $transaction, string $reason): void
    {
        // Mark transaction as flagged
        $transaction->update(['flagged' => true]);

        $this->compliance->create([
            'transaction_id' => $transaction->id,
            'reason'         => $reason,
        ]);
    }

    public function all(array $filters = [])
    {
        return $this->compliance->all($filters);
    }

    public function findById(int $id)
    {
        return $this->compliance->findById($id);
    }

    public function reviewLog(int $id, ?string $notes = null): bool
    {
        return $this->compliance->update($id, [
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'notes'       => $notes,
        ]);
    }

    public function pending()
    {
        return $this->compliance->pending();
    }
}
