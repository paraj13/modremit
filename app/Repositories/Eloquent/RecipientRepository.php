<?php

namespace App\Repositories\Eloquent;

use App\Models\Recipient;
use App\Repositories\Contracts\RecipientRepositoryInterface;

class RecipientRepository implements RecipientRepositoryInterface
{
    public function allForCustomer(int $customerId)
    {
        return Recipient::where('customer_id', $customerId)->latest()->get();
    }

    public function allForAgent(int $agentId)
    {
        return Recipient::whereHas('customer', function($q) use ($agentId) {
            $q->where('agent_id', $agentId);
        })->with('customer')->latest()->get();
    }

    public function findById(int $id): ?Recipient
    {
        return Recipient::with('customer')->find($id);
    }

    public function findByIdForCustomer(int $id, int $customerId): ?Recipient
    {
        return Recipient::where('id', $id)->where('customer_id', $customerId)->first();
    }

    public function create(array $data): Recipient
    {
        return Recipient::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Recipient::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Recipient::destroy($id) > 0;
    }
}
