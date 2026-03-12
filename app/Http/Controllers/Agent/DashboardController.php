<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Services\CustomerService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private TransactionService $transactionService,
        private CustomerService $customerService,
        private WalletService $walletService
    ) {}

    public function index(\Illuminate\Http\Request $request)
    {
        $agentId = Auth::id();
        $params = [
            'month' => $request->get('month', date('m')),
            'year'  => $request->get('year', date('Y')),
        ];

        $stats = $this->transactionService->getStatsForAgent($agentId, $params);
        $customerCount = $this->customerService->listForAgent()->total();
        $wallet = $this->walletService->getForAgent($agentId);
        $recentTransactions = $this->transactionService->listForAgent(['limit' => 5]);

        return view('agent.dashboard', compact('stats', 'customerCount', 'wallet', 'recentTransactions', 'params'));
    }
}
