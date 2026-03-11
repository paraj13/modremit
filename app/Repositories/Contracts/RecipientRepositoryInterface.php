<?php

namespace App\Repositories\Contracts;

interface RecipientRepositoryInterface
{
    public function allForCustomer(int $customerId);
    public function findById(int $id): ?\App\Models\Recipient;
    public function findByIdForCustomer(int $id, int $customerId): ?\App\Models\Recipient;
    public function create(array $data): \App\Models\Recipient;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
