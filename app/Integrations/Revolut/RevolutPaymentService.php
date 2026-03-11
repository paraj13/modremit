<?php

namespace App\Integrations\Revolut;

class RevolutPaymentService
{
    public function __construct(private RevolutClient $client) {}

    /**
     * Send INR payment to a recipient's bank/UPI.
     * Returns the payment reference ID.
     */
    public function sendPayment(array $data): string
    {
        if (config('integrations.revolut.api_key') === 'dummy_revolut_api_key') {
            return 'DUM-PAY-' . strtoupper(uniqid());
        }

        $payload = [
            'request_id'  => uniqid('pay_', true),
            'account_id'  => config('integrations.revolut.account_id'),
            'receiver'    => [
                'beneficiary_name' => $data['recipient_name'],
                'account_number'   => $data['account_number'] ?? null,
                'sort_code'        => $data['ifsc_code'] ?? null,
                'upi_id'           => $data['upi_id'] ?? null,
            ],
            'amount'      => (int) round($data['amount'] * 100), // in paise
            'currency'    => 'INR',
            'reference'   => $data['reference'] ?? 'MODREMIT',
        ];

        $response = $this->client->post('pay', $payload);

        return $response['id'] ?? 'UNKNOWN-' . uniqid();
    }
}
