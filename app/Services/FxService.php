<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Repositories\Contracts\FxQuoteRepositoryInterface;
use App\Integrations\Revolut\RevolutFxService;

class FxService
{
    public function __construct(
        private RevolutFxService $revolutFx,
        private FxQuoteRepositoryInterface $fxQuotes
    ) {}

    /** Fetch a live quote from Revolut and persist it */
    public function fetchAndStoreQuote(float $chfAmount, int $agentId): \App\Models\FxQuote
    {
        $quoteData = $this->revolutFx->fetchQuote($chfAmount);

        // Apply commission (e.g. 2%)
        $commissionRate = (float) config('remittance.commission_rate', 2);
        $commission = round($chfAmount * ($commissionRate / 100), 2);
        $netChf = $chfAmount - $commission;
        $inrAmount = round($netChf * $quoteData['rate'], 2);

        return $this->fxQuotes->create([
            'agent_id'      => $agentId,
            'revolut_quote_id' => $quoteData['quote_id'] ?? null,
            'chf_amount'    => $chfAmount,
            'inr_amount'    => $inrAmount,
            'rate'          => $quoteData['rate'],
            'fee'           => $commission,
            'expires_at'    => $quoteData['expires_at'] ?? now()->addMinutes(5),
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
