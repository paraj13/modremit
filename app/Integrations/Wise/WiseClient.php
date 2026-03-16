<?php

namespace App\Integrations\Wise;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WiseClient
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.wise.sandbox', true)
            ? 'https://api.sandbox.transferwise.tech'
            : 'https://api.transferwise.com';

        $this->apiKey = (string) config('services.wise.api_key', '');
    }

    public function get(string $endpoint, array $query = []): array
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->get($this->baseUrl . $endpoint, $query);

            $body = $response->json() ?? [];

            Log::info('Wise API GET', [
                'endpoint' => $endpoint,
                'status'   => $response->status(),
                'response' => $body,
            ]);

            if ($response->failed()) {
                throw new \RuntimeException(
                    'Wise API error: ' . ($body['message'] ?? $response->status())
                );
            }

            return $body;
        } catch (\Exception $e) {
            Log::error('Wise API GET failed', [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        try {
            $request = Http::withToken($this->apiKey)
                ->acceptJson()
                ->withHeaders($headers);

            $response = $request->post($this->baseUrl . $endpoint, $data);

            $body = $response->json() ?? [];

            Log::info('Wise API POST', [
                'endpoint' => $endpoint,
                'payload'  => $data,
                'status'   => $response->status(),
                'response' => $body,
            ]);

            if ($response->failed()) {
                throw new \RuntimeException(
                    'Wise API error: ' . ($body['message'] ?? json_encode($body['errors'] ?? $response->status()))
                );
            }

            return $body;
        } catch (\Exception $e) {
            Log::error('Wise API POST failed', [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
