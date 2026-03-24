<?php

namespace App\Integrations\Sumsub;

use App\Models\Customer;
use Illuminate\Support\Str;

class SumsubKycService
{
    public function __construct(private SumsubClient $client) {}

    /** Create an applicant in Sumsub and return the applicant ID */
    public function createApplicant(Customer $customer): ?string
    {
        if (config('integrations.sumsub.app_token') === 'dummy_sumsub_app_token') {
            return 'dummy_applicant_' . $customer->id . '_' . uniqid();
        }

        $level = config('integrations.sumsub.level_name', 'id-and-liveness');
        $externalId = $this->getExternalId($customer);

        try {
            $response = $this->client->post("resources/applicants?levelName={$level}", [
                'externalUserId' => $externalId,
                'email'          => $customer->email,
                'phone'          => $customer->phone,
                'fixedInfo'      => [
                    'firstName' => explode(' ', $customer->name)[0] ?? $customer->name,
                    'lastName'  => explode(' ', $customer->name, 2)[1] ?? '',
                ],
            ]);
            return $response['id'] ?? null;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 409) {
                logger()->info("Sumsub applicant already exists for {$externalId}, fetching existing ID.");
                return $this->getApplicantIdByExternalId($externalId);
            }
            throw $e;
        }
    }

    /** Create an applicant in Sumsub for an Agent */
    public function createAgentApplicant(\App\Models\User $agent): ?string
    {
        if (config('integrations.sumsub.app_token') === 'dummy_sumsub_app_token') {
            return 'dummy_applicant_agent_' . $agent->id . '_' . uniqid();
        }

        $level = config('integrations.sumsub.level_name', 'id-and-liveness');
        $externalId = $this->getExternalId($agent);

        try {
            $response = $this->client->post("resources/applicants?levelName={$level}", [
                'externalUserId' => $externalId,
                'email'          => $agent->email,
                'phone'          => $agent->phone ?? '',
                'fixedInfo'      => [
                    'firstName' => explode(' ', $agent->name)[0] ?? $agent->name,
                    'lastName'  => explode(' ', $agent->name, 2)[1] ?? '',
                ],
            ]);
            return $response['id'] ?? null;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 409) {
                logger()->info("Sumsub agent applicant already exists for {$externalId}, fetching existing ID.");
                return $this->getApplicantIdByExternalId($externalId);
            }
            throw $e;
        }
    }

    /** Helper to find applicant ID by externalUserId */
    public function getApplicantIdByExternalId(string $externalId): ?string
    {
        try {
            // Sumsub endpoint to get applicant data by externalUserId
            $response = $this->client->get("resources/applicants/-;externalUserId={$externalId}/one");
            return $response['id'] ?? null;
        } catch (\Exception $e) {
            logger()->error("Failed to fetch existing Sumsub applicant", ['externalId' => $externalId, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /** Generate a hosted WebSDK verification link for an applicant */
    public function generateWebSdkLink(string $applicantId): ?string
    {
        if (str_starts_with($applicantId, 'dummy_applicant_')) {
            return "https://cockpit.sumsub.com/checktest/sdk-link?applicantId={$applicantId}";
        }

        $level = config('integrations.sumsub.level_name', 'basic-kyc');
        $customer = Customer::where('sumsub_applicant_id', $applicantId)->first();
        $identifiers = [];

        if (!$customer) {
            $user = \App\Models\User::where('sumsub_applicant_id', $applicantId)->first();
            $externalUserId = $user ? $this->getExternalId($user) : 'unknown';
            $redirectUrl = config('integrations.sumsub.redirect_url') ?: config('app.url') . '/dashboard';
            if ($user) {
                $identifiers = [
                    'email' => $user->email,
                    'phone' => $user->phone ?? ''
                ];
            }
        } else {
            $externalUserId = $this->getExternalId($customer);
            $redirectUrl = config('integrations.sumsub.redirect_url') ?: config('app.url') . '/customer/dashboard';
            $identifiers = [
                'email' => $customer->email,
                'phone' => $customer->phone
            ];
        }

        try {
            return $this->requestWebSdkLink($level, $externalUserId, $identifiers, $redirectUrl);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 404) {
                $errorBody = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (($errorBody['description'] ?? '') === 'Applicant is deactivated') {
                    logger()->info("Applicant {$applicantId} is deactivated, attempting reactivation.");
                    if ($this->activateApplicant($applicantId)) {
                        return $this->requestWebSdkLink($level, $externalUserId, $identifiers, $redirectUrl);
                    }
                }
            }
            throw $e;
        }
    }

    /** Private helper to perform the actual link request */
    private function requestWebSdkLink(string $level, string $userId, array $identifiers = [], ?string $redirectUrl = null): ?string
    {
        $payload = [
            'levelName' => $level,
            'userId'    => $userId,
            'ttlInSecs' => 1800, // 30 minutes as per recommended integration
        ];

        if (!empty($identifiers)) {
            $payload['applicantIdentifiers'] = $identifiers;
        }

        if ($redirectUrl) {
            // Updated structure to use the redirect object as required by latest Sumsub SDK
            $payload['redirect'] = [
                'successUrl' => $redirectUrl,
                'rejectUrl'  => $redirectUrl, // Can be customized later
            ];
        }

        $response = $this->client->post("resources/sdkIntegrations/levels/-/websdkLink", $payload);

        return $response['url'] ?? null;
    }

    /** Helper to reactivate a deactivated applicant */
    public function activateApplicant(string $applicantId): bool
    {
        try {
            // Try to activate via presence endpoint
            $this->client->patch("resources/applicants/{$applicantId}/presence", [
                'status' => 'active'
            ]);
            return true;
        } catch (\Exception $e) {
            logger()->error("Failed to activate Sumsub applicant", ['applicantId' => $applicantId, 'error' => $e->getMessage()]);
            return false;
        }
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

            // Notify the customer
            try {
                \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\KycStatusMail($customer, $kycStatus, $payload['reviewResult']['reviewRejectType'] ?? null));
            } catch (\Exception $e) {
                logger()->error("Failed to send KYC status email to customer", ['error' => $e->getMessage()]);
            }

            // Notify the agent
            $customer->agent?->notify(new \App\Notifications\KycStatusUpdated($customer, $kycStatus));
            return;
        }

        $agent = \App\Models\User::role('agent')->where('sumsub_applicant_id', $applicantId)->first();
        if ($agent) {
            $agent->update(['kyc_status' => $kycStatus]);
            
            // Notify the agent
            try {
                \Illuminate\Support\Facades\Mail::to($agent->email)->send(new \App\Mail\KycStatusMail($agent, $kycStatus, $payload['reviewResult']['reviewRejectType'] ?? null));
            } catch (\Exception $e) {
                logger()->error("Failed to send KYC status email to agent", ['error' => $e->getMessage()]);
            }
        }
    }

    /** Helper to generate a unique external ID including name to avoid env collisions */
    private function getExternalId($model): string
    {
        $type = ($model instanceof Customer) ? 'customer' : 'agent';
        $nameSlug = Str::slug($model->name);
        return "{$type}_{$model->id}_{$nameSlug}";
    }
}
