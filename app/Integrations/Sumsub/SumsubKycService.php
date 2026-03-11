<?php

namespace App\Integrations\Sumsub;

use App\Models\Customer;

class SumsubKycService
{
    public function __construct(private SumsubClient $client) {}

    /** Create an applicant in Sumsub and return the applicant ID */
    public function createApplicant(Customer $customer): ?string
    {
        if (config('integrations.sumsub.app_token') === 'dummy_sumsub_app_token') {
            return 'dummy_applicant_' . $customer->id . '_' . uniqid();
        }

        $level = config('integrations.sumsub.level_name', 'basic-kyc-level');

        $response = $this->client->post("resources/applicants?levelName={$level}", [
            'externalUserId' => 'customer_' . $customer->id,
            'email'          => $customer->email,
            'phone'          => $customer->phone,
            'fixedInfo'      => [
                'firstName' => explode(' ', $customer->name)[0] ?? $customer->name,
                'lastName'  => explode(' ', $customer->name, 2)[1] ?? '',
            ],
        ]);

        return $response['id'] ?? null;
    }

    /** Get the KYC verification status for an applicant */
    public function getStatus(string $applicantId): ?string
    {
        if (str_starts_with($applicantId, 'dummy_applicant_')) {
            // Simulate random KYC result in dummy mode
            $statuses = ['pending', 'approved', 'rejected'];
            return $statuses[array_rand($statuses)];
        }

        $response = $this->client->get("resources/applicants/{$applicantId}/requiredIdDocsStatus");
        return $response['reviewResult']['reviewAnswer'] ?? 'pending';
    }

    /** Handle a Sumsub webhook payload */
    public function handleWebhook(array $payload): void
    {
        $applicantId    = $payload['applicantId'] ?? null;
        $reviewAnswer   = $payload['reviewResult']['reviewAnswer'] ?? null;

        if (!$applicantId || !$reviewAnswer) {
            return;
        }

        $kycStatus = match (strtolower($reviewAnswer)) {
            'green' => 'approved',
            'red'   => 'rejected',
            default => 'pending',
        };

        $customer = Customer::where('sumsub_applicant_id', $applicantId)->first();
        if ($customer) {
            $customer->update(['kyc_status' => $kycStatus]);

            // Notify the agent
            $customer->agent?->notify(new \App\Notifications\KycStatusUpdated($customer, $kycStatus));
        }
    }
}
