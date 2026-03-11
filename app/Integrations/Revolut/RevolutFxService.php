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
    public function fetchQuote(float $chfAmount): array
    {
        // In sandbox/dummy mode, simulate a realistic CHF→INR rate
        if (config('integrations.revolut.api_key') === 'dummy_revolut_api_key') {
            return $this->dummyQuote($chfAmount);
        }

        $payload = [
            'from'     => ['currency' => 'CHF', 'amount' => (int) round($chfAmount * 100)], // in cents
            'to'       => ['currency' => 'INR'],
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

    private function dummyQuote(float $chfAmount): array
    {
        // Simulate CHF/INR rate around 95–97
        $rate = round(95 + (mt_rand(0, 200) / 100), 4);
        return [
            'quote_id'   => 'dummy_quote_' . uniqid(),
            'rate'       => $rate,
            'expires_at' => now()->addMinutes(5),
        ];
    }
}
