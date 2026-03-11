<?php

namespace App\Repositories\Contracts;

interface ComplianceRepositoryInterface
{
    public function all(array $filters = []);
    public function findById(int $id): ?\App\Models\ComplianceLog;
    public function create(array $data): \App\Models\ComplianceLog;
    public function update(int $id, array $data): bool;
    public function pending();
}
