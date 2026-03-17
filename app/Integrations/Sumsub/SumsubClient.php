<?php

namespace App\Integrations\Sumsub;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SumsubClient
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => rtrim(config('integrations.sumsub.base_url'), '/') . '/',
            'timeout'  => 30,
        ]);
    }

    protected function buildHeaders(string $method, string $path, string $body = ''): array
    {
        $ts     = (string) time();
        $secret = config('integrations.sumsub.secret_key');
        $token  = config('integrations.sumsub.app_token');

        $signature = hash_hmac('sha256', $ts . strtoupper($method) . $path . $body, $secret);

        return [
            'X-App-Token'   => $token,
            'X-App-Access-Sig' => $signature,
            'X-App-Access-Ts'  => $ts,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    public function post(string $path, array $data = []): array
    {
        try {
            $body     = !empty($data) ? json_encode($data) : '';
            $options  = [
                'headers' => $this->buildHeaders('POST', '/' . ltrim($path, '/'), $body),
            ];

            if ($body !== '') {
                $options['body'] = $body;
            }

            $response = $this->http->post($path, $options);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (RequestException $e) {
            logger()->error('Sumsub post error', ['path' => $path, 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function get(string $path): array
    {
        try {
            $response = $this->http->get($path, [
                'headers' => $this->buildHeaders('GET', '/' . ltrim($path, '/')),
            ]);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (RequestException $e) {
            logger()->error('Sumsub get error', ['path' => $path, 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getRaw(string $path): string
    {
        try {
            $response = $this->http->get($path, [
                'headers' => $this->buildHeaders('GET', '/' . ltrim($path, '/')),
            ]);
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            logger()->error('Sumsub getRaw error', ['path' => $path, 'message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function patch(string $path, array $data = []): array
    {
        try {
            $body     = !empty($data) ? json_encode($data) : '';
            $options  = [
                'headers' => $this->buildHeaders('PATCH', '/' . ltrim($path, '/'), $body),
            ];

            if ($body !== '') {
                $options['body'] = $body;
            }

            $response = $this->http->patch($path, $options);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (RequestException $e) {
            logger()->error('Sumsub patch error', ['path' => $path, 'message' => $e->getMessage()]);
            throw $e;
        }
    }
}
