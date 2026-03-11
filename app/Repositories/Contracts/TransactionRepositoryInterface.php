<?php

namespace App\Repositories\Contracts;

interface TransactionRepositoryInterface
{
    public function allForAgent(int $agentId, array $filters = []);
    public function all(array $filters = []);
    public function findById(int $id): ?\App\Models\Transaction;
    public function create(array $data): \App\Models\Transaction;
    public function update(int $id, array $data): bool;
    public function getFlagged();
    public function getStats(): array;
    public function getStatsForAgent(int $agentId): array;
}
