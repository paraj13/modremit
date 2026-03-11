<?php

namespace App\Integrations\Revolut;

use Carbon\Carbon;

class RevolutFxService
{
    public function __construct(private RevolutClient $client) {}

    /**
     * Fetch an FX quote from Revolut.
     * Returns ['quote_id', 'rate', 'expires_at']
     */
    public function fetchQuote(float $chfAmount, string $targetCurrency = 'INR'): array
    {
        // In sandbox/dummy mode, simulate a realistic CHF→Target rate
        if (config('integrations.revolut.api_key') === 'dummy_revolut_api_key') {
            return $this->dummyQuote($chfAmount, $targetCurrency);
        }

        $payload = [
            'from'     => ['currency' => 'CHF', 'amount' => (int) round($chfAmount * 100)], // in cents
            'to'       => ['currency' => $targetCurrency],
            'request_id' => uniqid('quote_', true),
        ];

        $response = $this->client->post('exchange/quote', $payload);

        return [
            'quote_id'   => $response['id'] ?? null,
            'rate'       => $response['rate'] ?? 95.0,
            'expires_at' => isset($response['valid_until'])
                ? Carbon::parse($response['valid_until'])
                : now()->addMinutes(5),
        ];
    }

    public function executeExchange(string $quoteId): bool
    {
        if (config('integrations.revolut.api_key') === 'dummy_revolut_api_key') {
            return true;
        }

        $response = $this->client->post('exchange/order', [
            'quote_id' => $quoteId,
        ]);

        return ($response['state'] ?? '') === 'completed';
    }

    private function dummyQuote(float $chfAmount, string $targetCurrency): array
    {
        // Map some realistic base rates relative to CHF
        $baseRates = [
            'INR' => 95.0,
            'EUR' => 1.05,
            'USD' => 1.12,
            'GBP' => 0.88,
            'PKR' => 310.0,
            'PHP' => 62.0
        ];

        $base = $baseRates[$targetCurrency] ?? 1.0;
        $rate = round($base + (mt_rand(-500, 500) / 10000) * $base, 4);

        return [
            'quote_id'   => 'dummy_quote_' . uniqid(),
            'rate'       => $rate,
            'expires_at' => now()->addMinutes(5),
            'to_currency' => $targetCurrency
        ];
    }
}
