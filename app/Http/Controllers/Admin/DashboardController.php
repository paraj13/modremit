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

    public function index()
    {
        $stats = $this->transactionService->getStats();
        $pendingCompliance = $this->complianceService->pending()->count();

        return view('admin.dashboard', compact('stats', 'pendingCompliance'));
    }
}
