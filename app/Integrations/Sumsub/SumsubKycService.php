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
            print("Dummy mode enabled, returning dummy applicant ID");
            return 'dummy_applicant_' . $customer->id . '_' . uniqid();
        }

        $level = config('integrations.sumsub.level_name', 'id-and-liveness');

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

    /** Generate a hosted WebSDK verification link for an applicant */
    public function generateWebSdkLink(string $applicantId): ?string
    {
        if (str_starts_with($applicantId, 'dummy_applicant_')) {
            return "https://cockpit.sumsub.com/checktest/sdk-link?applicantId={$applicantId}";
        }

        $level = config('integrations.sumsub.level_name', 'basic-kyc');
        $customer = Customer::where('sumsub_applicant_id', $applicantId)->first();
        $externalUserId = $customer ? 'customer_' . $customer->id : 'unknown';

        $response = $this->client->post("resources/sdkIntegrations/levels/-/websdkLink", [
            'levelName' => $level,
            'userId'    => $externalUserId,
            'ttlInSecs' => 86400, // 24 hours
        ]);

        return $response['url'] ?? null;
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

    /** Retrieve detailed KYC data including image links */
    public function getApplicantData(string $applicantId): array
    {
        if (str_starts_with($applicantId, 'dummy_applicant_')) {
            return [
                'document_type' => 'PASSPORT',
                'doc_front' => 'https://ui-avatars.com/api/?name=Pass+Front&background=random&size=400',
                'doc_back' => null,
                'selfie' => 'https://ui-avatars.com/api/?name=Selfie+IMG&background=random&size=400',
            ];
        }

        try {
            // Get Applicant Metadata to find image IDs
            $metadata = $this->client->get("resources/applicants/{$applicantId}/metadata/resources");
            $docType = 'UNKNOWN';
            $frontImageId = null;
            $backImageId = null;
            $selfieImageId = null;

            // Extract the items array
            $items = $metadata['items'] ?? [];

            foreach ($items as $item) {
                // Determine document types from idDocDef
                $def = $item['idDocDef'] ?? [];
                $type = $def['idDocType'] ?? null;
                $subType = $def['idDocSubType'] ?? null;
                
                // Also check root for alternate structures
                $internalDocType = $item['docType'] ?? null;

                // Set main document type, ignoring selfie
                if ($type && !in_array($type, ['SELFIE', 'VIDEO_SELFIE', 'FACE', 'LIVENESS'])) {
                    $docType = $type;
                }
                
                // Identify if image is a Selfie or a Document side
                if (in_array($type, ['SELFIE', 'VIDEO_SELFIE', 'FACE', 'LIVENESS']) || $internalDocType === 'face') {
                    $selfieImageId = $item['id'];
                } else {
                    if ($subType === 'FRONT_SIDE' || (isset($item['side']) && strtolower($item['side']) === 'front')) {
                        $frontImageId = $item['id'];
                    } elseif ($subType === 'BACK_SIDE' || (isset($item['side']) && strtolower($item['side']) === 'back')) {
                        $backImageId = $item['id'];
                    } elseif ($frontImageId === null) {
                        $frontImageId = $item['id']; // fallback
                    }
                }
            }
            return [
                'document_type' => $docType,
                'doc_front' => $frontImageId ? $this->getSecureImageBase64($applicantId, $frontImageId) : null,
                'doc_back'  => $backImageId ? $this->getSecureImageBase64($applicantId, $backImageId) : null,
                'selfie'    => $selfieImageId ? $this->getSecureImageBase64($applicantId, $selfieImageId) : null,
            ];
        } catch (\Exception $e) {
            logger()->error("Sumsub KYC Data Fetch Error", ['error' => $e->getMessage()]);
            return [
                'document_type' => 'ERROR_FETCHING',
                'doc_front' => null,
                'doc_back' => null,
                'selfie' => null,
            ];
        }
    }

    /** 
     * Helper to fetch an image and convert it to Data URI for secure display 
     * without exposing backend Sumsub tokens to the frontend
     */
    private function getSecureImageBase64(string $applicantId, string $imageId): ?string
    {
        try {
             $imageData = $this->client->getRaw("resources/inspections/{$applicantId}/resources/{$imageId}");
             $base64 = base64_encode($imageData);
             return "data:image/jpeg;base64,{$base64}";
        } catch (\Exception $e) {
            return null;
        }
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
