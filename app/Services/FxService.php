<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Repositories\Contracts\FxQuoteRepositoryInterface;

class FxService
{
    public function __construct(
        private FxRatesService $fxRates,
        private FxQuoteRepositoryInterface $fxQuotes
    ) {}

    /** Fetch a live quote and persist it */
    public function fetchAndStoreQuote(
        float $amount, 
        ?int $agentId = null, 
        string $targetCurrency = 'INR', 
        string $fromCurrency = 'CHF',
        string $calculationType = 'send' // 'send' or 'receive'
    ): \App\Models\FxQuote {
        // Use the unified FX service for consistency
        $fxData = $this->fxRates->getRate($fromCurrency, $targetCurrency, false);
        $rate = (float) $fxData['rate'];
        $quoteId = $fxData['id'] ?? null;

        $commissionRate = (float) config('remittance.commission_rate', 1);
        
        if ($calculationType === 'receive') {
            // We want the recipient to get exactly $amount in target currency
            // targetAmount = (chfAmount - commission) * rate
            // chfAmount - commission = targetAmount / rate
            // chfAmount * (1 - commissionRate/100) = targetAmount / rate
            // chfAmount = (targetAmount / rate) / (1 - commissionRate/100)
            
            $netAmount = $amount / $rate;
            $chfAmount = $netAmount / (1 - ($commissionRate / 100));
            $totalCommission = $chfAmount - $netAmount;
            
            $sendAmount = $netAmount;
            $targetAmount = $amount;
        } else {
            // Standard: User pays $amount in source currency
            $chfAmount = $amount;
            $totalCommission = round($chfAmount * ($commissionRate / 100), 2);
            $sendAmount = $chfAmount - $totalCommission;
            $targetAmount = round($sendAmount * $rate, 2);
        }

        // Split commission (Default 60% to agent, 40% to admin)
        $agentSplit = (float) config('remittance.agent_commission_split', 60);
        $agentCommission = round($totalCommission * ($agentSplit / 100), 2);
        $adminCommission = $totalCommission - $agentCommission;

        return $this->fxQuotes->create([
            'agent_id'         => $agentId,
            'quote_id'         => $quoteId ?? null,
            'chf_amount'       => round($chfAmount, 2),
            'send_amount'      => round($sendAmount, 2),
            'target_amount'    => round($targetAmount, 2),
            'from_currency'    => $fromCurrency,
            'to_currency'      => $targetCurrency,
            'rate'             => $rate,
            'fee'              => round($totalCommission, 2),
            'agent_commission' => $agentCommission,
            'admin_commission' => $adminCommission,
            'expires_at'       => now()->addMinutes(5),
        ]);
    }

    public function recent(int $limit = 20)
    {
        return $this->fxQuotes->recent($limit);
    }

    public function all(array $filters = [])
    {
        return $this->fxQuotes->all($filters);
    }

    public function findById(int $id)
    {
        return $this->fxQuotes->findById($id);
    }
}
