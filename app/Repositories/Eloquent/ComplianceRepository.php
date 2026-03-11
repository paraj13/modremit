<?php

namespace App\Repositories\Eloquent;

use App\Models\ComplianceLog;
use App\Repositories\Contracts\ComplianceRepositoryInterface;

class ComplianceRepository implements ComplianceRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = ComplianceLog::with(['transaction', 'reviewer'])->latest();
        return $query->paginate(20);
    }

    public function findById(int $id): ?ComplianceLog
    {
        return ComplianceLog::with(['transaction.customer', 'transaction.agent', 'reviewer'])->find($id);
    }

    public function create(array $data): ComplianceLog
    {
        return ComplianceLog::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return ComplianceLog::where('id', $id)->update($data) > 0;
    }

    public function pending()
    {
        return ComplianceLog::with(['transaction.customer', 'transaction.agent'])
            ->whereNull('reviewed_by')
            ->latest()
            ->get();
    }
}
