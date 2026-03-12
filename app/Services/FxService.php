<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Repositories\Contracts\FxQuoteRepositoryInterface;
use App\Integrations\Revolut\RevolutFxService;

class FxService
{
    public function __construct(
        private FxRatesService $fxRates,
        private FxQuoteRepositoryInterface $fxQuotes
    ) {}

    /** Fetch a live quote from Revolut and persist it */
    public function fetchAndStoreQuote(float $chfAmount, ?int $agentId = null, string $targetCurrency = 'INR'): \App\Models\FxQuote
    {
        // Use the unified FX service for consistency
        // For actual transfers, we fetch a fresh rate (cache = false)
        $fxData = $this->fxRates->getRate('CHF', $targetCurrency, false);
        $rate = $fxData['rate'];

        // Apply commission (e.g. 2%)
        $commissionRate = (float) config('remittance.commission_rate', 2);
        $totalCommission = round($chfAmount * ($commissionRate / 100), 2);
        
        // Split commission (Default 60% to agent, 40% to admin)
        $agentSplit = (float) config('remittance.agent_commission_split', 60);
        $agentCommission = round($totalCommission * ($agentSplit / 100), 2);
        $adminCommission = $totalCommission - $agentCommission;

        $sendAmount = $chfAmount - $totalCommission;
        $targetAmount = round($sendAmount * $rate, 2);

        return $this->fxQuotes->create([
            'agent_id'         => $agentId,
            'revolut_quote_id' => null, // Unified flow uses the rate directly
            'chf_amount'       => $chfAmount, // Total Paid
            'send_amount'      => $sendAmount,
            'target_amount'    => $targetAmount,
            'from_currency'    => 'CHF',
            'to_currency'      => $targetCurrency,
            'rate'             => $rate,
            'fee'              => $totalCommission,
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
