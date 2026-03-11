<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function allForAgent(int $agentId, array $filters = [])
    {
        $query = Transaction::with(['customer', 'recipient', 'fxQuote'])
            ->where('agent_id', $agentId)
            ->latest();
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query->paginate(15);
    }

    public function all(array $filters = [])
    {
        $query = Transaction::with(['customer', 'recipient', 'agent', 'fxQuote'])->latest();
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['flagged'])) {
            $query->where('flagged', true);
        }
        return $query->paginate(20);
    }

    public function findById(int $id): ?Transaction
    {
        return Transaction::with(['customer', 'recipient', 'agent', 'fxQuote', 'complianceLogs'])->find($id);
    }

    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Transaction::where('id', $id)->update($data) > 0;
    }

    public function getFlagged()
    {
        return Transaction::with(['customer', 'agent', 'complianceLogs'])
            ->where('flagged', true)
            ->latest()
            ->paginate(20);
    }

    public function getStats(): array
    {
        return [
            'total'        => Transaction::count(),
            'total_chf'    => Transaction::sum('chf_amount'),
            'total_target'    => Transaction::sum('target_amount'),
            'total_commission' => Transaction::sum('commission'),
            'failed'       => Transaction::where('status', 'failed')->count(),
            'flagged'      => Transaction::where('flagged', true)->count(),
            'pending'      => Transaction::where('status', 'pending')->count(),
            'completed'    => Transaction::where('status', 'completed')->count(),
        ];
    }

    public function getStatsForAgent(int $agentId): array
    {
        return [
            'total'        => Transaction::where('agent_id', $agentId)->count(),
            'total_chf'    => Transaction::where('agent_id', $agentId)->sum('chf_amount'),
            'total_target'    => Transaction::where('agent_id', $agentId)->sum('target_amount'),
            'total_commission' => Transaction::where('agent_id', $agentId)->sum('commission'),
            'failed'       => Transaction::where('agent_id', $agentId)->where('status', 'failed')->count(),
            'pending'      => Transaction::where('agent_id', $agentId)->where('status', 'pending')->count(),
            'completed'    => Transaction::where('agent_id', $agentId)->where('status', 'completed')->count(),
        ];
    }
}
