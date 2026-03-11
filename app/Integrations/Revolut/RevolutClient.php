<?php

namespace App\Integrations\Revolut;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RevolutClient
{
    protected Client $http;
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('integrations.revolut.base_url'), '/');
        $this->http = new Client([
            'base_uri'    => $this->baseUrl . '/',
            'timeout'     => 30,
            'headers'     => [
                'Authorization' => 'Bearer ' . config('integrations.revolut.api_key'),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
        ]);
    }

    public function post(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->http->post($endpoint, ['json' => $data]);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (RequestException $e) {
            logger()->error('Revolut API post error', [
                'endpoint' => $endpoint,
                'message'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function get(string $endpoint, array $query = []): array
    {
        try {
            $response = $this->http->get($endpoint, ['query' => $query]);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (RequestException $e) {
            logger()->error('Revolut API get error', [
                'endpoint' => $endpoint,
                'message'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
