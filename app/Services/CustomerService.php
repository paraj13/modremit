<?php

namespace App\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Integrations\Sumsub\SumsubKycService;
use App\Mail\VerifyKycMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CustomerService
{
    public function __construct(
        private CustomerRepositoryInterface $customers,
        private SumsubKycService $kyc
    ) {}

    public function listForAgent(array $filters = [])
    {
        return $this->customers->allForAgent(Auth::id(), $filters);
    }

    public function listAll(array $filters = [])
    {
        return $this->customers->all($filters);
    }

    public function findOwned(int $id)
    {
        return $this->customers->findByIdForAgent($id, Auth::id());
    }

    public function find(int $id)
    {
        return $this->customers->findById($id);
    }

    public function create(array $data)
    {
        $data['agent_id'] = Auth::id();
        $customer = $this->customers->create($data);
        print("Customer created: " . $customer->id);

        // Trigger KYC submission asynchronously (wrapped in try-catch for dummy keys)
        try {
            $applicantId = $this->kyc->createApplicant($customer);
            if ($applicantId) {
                $this->customers->update($customer->id, [
                    'sumsub_applicant_id' => $applicantId,
                    'kyc_status' => 'pending',
                ]);
                
                $customer->refresh();

                
                // Generate WebSDK link and send email
                $verificationLink = $this->kyc->generateWebSdkLink($applicantId);
                logger()->info('Sumsub applicant created', ['applicantId' => $applicantId]);
                if ($verificationLink) {
                    Mail::to($customer->email)->send(new VerifyKycMail($customer, $verificationLink));
                    print("Verification email sent to: " . $customer->email);
                }
            }
        } catch (\Exception $e) {
            // Log but don't fail the customer creation
            logger()->warning('Sumsub applicant creation or email failed', ['error' => $e->getMessage()]);
        }

        return $customer;
    }

    public function update(int $id, array $data)
    {
        return $this->customers->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->customers->delete($id);
    }

    public function refreshKycStatus(int $id)
    {
        $customer = $this->customers->findById($id);
        if (!$customer) {
            return null;
        }

        // If missing applicant ID, try to discover it by externalUserId
        if (!$customer->sumsub_applicant_id) {
            $externalId = 'customer_' . $customer->id;
            $discoveredId = $this->kyc->getApplicantIdByExternalId($externalId);
            if ($discoveredId) {
                $this->customers->update($id, ['sumsub_applicant_id' => $discoveredId]);
                $customer->refresh();
                logger()->info("Recovered missing Sumsub ID during sync", ['customer_id' => $id, 'applicant_id' => $discoveredId]);
            } else {
                return null;
            }
        }

        try {
            $status = $this->kyc->getStatus($customer->sumsub_applicant_id);
            if ($status) {
                $this->customers->update($id, ['kyc_status' => $status]);
                $customer->refresh();
            }

            // Resend verification email if still pending
            if ($customer->kyc_status === 'pending') {
                $verificationLink = $this->kyc->generateWebSdkLink($customer->sumsub_applicant_id);
                if ($verificationLink) {
                    Mail::to($customer->email)->send(new VerifyKycMail($customer, $verificationLink));
                    logger()->info('Verification email automatically resent on KYC sync', ['customer_id' => $customer->id]);
                }
            }

            return $status;
        } catch (\Exception $e) {
            logger()->warning('Sumsub status check failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
