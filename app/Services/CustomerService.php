<?php

namespace App\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Integrations\Sumsub\SumsubKycService;
use Illuminate\Support\Facades\Auth;

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

        // Trigger KYC submission asynchronously (wrapped in try-catch for dummy keys)
        try {
            $applicantId = $this->kyc->createApplicant($customer);
            if ($applicantId) {
                $this->customers->update($customer->id, [
                    'sumsub_applicant_id' => $applicantId,
                    'kyc_status' => 'pending',
                ]);
                $customer->refresh();
            }
        } catch (\Exception $e) {
            // Log but don't fail the customer creation
            logger()->warning('Sumsub applicant creation failed', ['error' => $e->getMessage()]);
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
        if (!$customer || !$customer->sumsub_applicant_id) {
            return null;
        }

        try {
            $status = $this->kyc->getStatus($customer->sumsub_applicant_id);
            if ($status) {
                $this->customers->update($id, ['kyc_status' => $status]);
            }
            return $status;
        } catch (\Exception $e) {
            logger()->warning('Sumsub status check failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
