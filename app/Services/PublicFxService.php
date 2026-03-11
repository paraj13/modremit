<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PublicFxService
{
    private string $baseUrl = 'https://open.er-api.com/v6/latest/';

    /**
     * Fetch a live quote for any currency pair.
     * 
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return array
     */
    public function getRate(string $from, string $to, float $amount): array
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        // Cache the rates for 60 minutes to reduce API load
        $rates = Cache::remember("fx_rates_{$from}", 3600, function () use ($from) {
            try {
                $response = Http::get($this->baseUrl . $from);
                
                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rates'] ?? null;
                }
                
                Log::error("Failed to fetch FX rates for {$from}: " . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error("Exception while fetching FX rates: " . $e->getMessage());
                return null;
            }
        });

        if (!$rates || !isset($rates[$to])) {
            return [
                'error' => 'Rate not available for this pair.',
                'rate' => 0,
                'result' => 0
            ];
        }

        $rate = (float) $rates[$to];
        
        // Dynamic fee calculation (e.g., 1.5% for public, can be customized)
        $feeRate = 0.015; 
        $fee = round($amount * $feeRate, 2);
        $netAmount = $amount - $fee;
        $result = round($netAmount * $rate, 2);

        return [
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'rate' => $rate,
            'fee' => $fee,
            'net_amount' => $netAmount,
            'result' => $result,
            'last_updated' => now()->toDateTimeString()
        ];
    }
}
