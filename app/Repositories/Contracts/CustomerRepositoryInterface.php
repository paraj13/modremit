<?php

namespace App\Repositories\Contracts;

interface CustomerRepositoryInterface
{
    public function allForAgent(int $agentId, array $filters = []);
    public function findById(int $id): ?\App\Models\Customer;
    public function findByIdForAgent(int $id, int $agentId): ?\App\Models\Customer;
    public function create(array $data): \App\Models\Customer;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function countForAgent(int $agentId): int;
    public function all(array $filters = []);
}
