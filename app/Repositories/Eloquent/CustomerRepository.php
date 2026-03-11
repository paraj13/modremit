<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function allForAgent(int $agentId, array $filters = [])
    {
        $query = Customer::where('agent_id', $agentId)->latest();
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }
        return $query->paginate(15);
    }

    public function all(array $filters = [])
    {
        $query = Customer::with('agent')->latest();
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
        return $query->paginate(20);
    }

    public function findById(int $id): ?Customer
    {
        return Customer::with(['recipients', 'agent'])->find($id);
    }

    public function findByIdForAgent(int $id, int $agentId): ?Customer
    {
        return Customer::where('id', $id)->where('agent_id', $agentId)->first();
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Customer::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Customer::destroy($id) > 0;
    }

    public function countForAgent(int $agentId): int
    {
        return Customer::where('agent_id', $agentId)->count();
    }
}
