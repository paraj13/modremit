<?php

namespace App\Services;

use App\Integrations\Revolut\RevolutFxService;
use Illuminate\Support\Facades\Cache;

class FxRatesService
{
    public function __construct(
        private RevolutFxService $revolutFx
    ) {}

    /**
     * Get a standardized rate for a currency pair.
     * Uses caching for non-transactional purposes (like landing page).
     */
    public function getRate(string $from = 'CHF', string $to = 'INR', bool $cache = true): array
    {
        $from = strtoupper($from);
        $to = strtoupper($to);
        $cacheKey = "fx_rate_{$from}_{$to}";

        if ($cache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // We use 1000 CHF as a base for rate calculation to get a representative rate
        $quote = $this->revolutFx->fetchQuote(1000, $to);
        
        $data = [
            'from' => $from,
            'to' => $to,
            'rate' => $quote['rate'],
            'last_updated' => now()->toDateTimeString(),
        ];

        if ($cache) {
            Cache::put($cacheKey, $data, 3600); // Cache for 1 hour
        }

        return $data;
    }
}
