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
        if (isset($filters['has_agent'])) {
            if ($filters['has_agent']) {
                $query->whereNotNull('agent_id');
            } else {
                $query->whereNull('agent_id');
            }
        }

        if (!empty($filters['limit'])) {
            return $query->limit($filters['limit'])->get();
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

    public function getStats(array $params = []): array
    {
        $query = Transaction::query();
        $this->applyDateFilter($query, $params);

        $stats = [
            'total'                  => (clone $query)->count(),
            'total_chf'              => (clone $query)->sum('chf_amount'),
            'total_send'             => (clone $query)->sum('send_amount'),
            'total_commission'       => (clone $query)->sum('commission'),
            'agent_commissions'      => (clone $query)->where('initiated_by', 'agent')->sum('agent_commission'),
            'admin_commissions'      => (clone $query)->sum('admin_commission'),
            'platform_from_agent'    => (clone $query)->where('initiated_by', 'agent')->sum('admin_commission'),
            'platform_from_customer' => (clone $query)->where('initiated_by', 'customer')->sum('commission'),
            'failed'                 => (clone $query)->where('status', 'failed')->count(),
            'flagged'                => (clone $query)->where('flagged', true)->count(),
            'pending'                => (clone $query)->where('status', 'pending')->count(),
            'completed'              => (clone $query)->where('status', 'completed')->count(),
        ];

        $stats['chart_data'] = $this->getChartData($params);

        return $stats;
    }

    public function getStatsForAgent(int $agentId, array $params = []): array
    {
        $query = Transaction::where('agent_id', $agentId);
        $this->applyDateFilter($query, $params);

        $stats = [
            'total'             => (clone $query)->count(),
            'total_chf'         => (clone $query)->sum('chf_amount'),
            'total_send'        => (clone $query)->sum('send_amount'),
            'total_commission'  => (clone $query)->sum('commission'),
            'agent_commission'  => (clone $query)->sum('agent_commission'),
            'failed'            => (clone $query)->where('status', 'failed')->count(),
            'pending'           => (clone $query)->where('status', 'pending')->count(),
            'completed'         => (clone $query)->where('status', 'completed')->count(),
        ];

        $stats['chart_data'] = $this->getChartData($params, $agentId);

        return $stats;
    }

    private function applyDateFilter($query, array $params)
    {
        $month = $params['month'] ?? date('m');
        $year = $params['year'] ?? date('Y');

        if ($month && $year) {
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        } elseif ($year) {
            $query->whereYear('created_at', $year);
        }
    }

    private function getChartData(array $params, ?int $agentId = null): array
    {
        $month = $params['month'] ?? date('m');
        $year = $params['year'] ?? date('Y');

        $query = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count'),
            DB::raw('sum(chf_amount) as volume'),
            DB::raw('sum(commission) as commission')
        );

        if ($agentId) {
            $query->where('agent_id', $agentId);
        }

        if ($month && $year) {
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        } elseif ($year) {
            $query->whereYear('created_at', $year);
        }

        $results = $query->groupBy('date')->orderBy('date')->get();

        return [
            'labels' => $results->pluck('date')->toArray(),
            'transactions' => $results->pluck('count')->toArray(),
            'volume' => $results->pluck('volume')->toArray(),
            'commissions' => $results->pluck('commission')->toArray(),
        ];
    }
}
