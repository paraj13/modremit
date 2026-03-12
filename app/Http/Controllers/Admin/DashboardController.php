<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Services\ComplianceService;

class DashboardController extends Controller
{
    public function __construct(
        private TransactionService $transactionService,
        private ComplianceService $complianceService
    ) {}

    public function index(\Illuminate\Http\Request $request)
    {
        $params = [
            'month' => $request->get('month', date('m')),
            'year'  => $request->get('year', date('Y')),
        ];

        $stats = $this->transactionService->getStats($params);
        $pendingCompliance = $this->complianceService->pending()->count();
        $pendingAgents = \App\Models\User::role('agent')->where('status', 'pending')->count();
        $recentTransactions = $this->transactionService->listAll(['limit' => 5]);

        return view('admin.dashboard', compact('stats', 'pendingCompliance', 'recentTransactions', 'pendingAgents', 'params'));
    }
}
