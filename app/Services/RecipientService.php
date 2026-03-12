<?php

namespace App\Services;

use App\Repositories\Contracts\RecipientRepositoryInterface;

class RecipientService
{
    public function __construct(
        private RecipientRepositoryInterface $recipients
    ) {}

    public function listForCustomer(int $customerId)
    {
        return $this->recipients->allForCustomer($customerId);
    }

    public function listForAgent(int $agentId)
    {
        return $this->recipients->allForAgent($agentId);
    }

    public function find(int $id)
    {
        return $this->recipients->findById($id);
    }

    public function findForCustomer(int $id, int $customerId)
    {
        return $this->recipients->findByIdForCustomer($id, $customerId);
    }

    public function create(int $customerId, array $data)
    {
        $data['customer_id'] = $customerId;
        return $this->recipients->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->recipients->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->recipients->delete($id);
    }
}
