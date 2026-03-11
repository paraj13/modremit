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

    public function index()
    {
        $agentId = Auth::id();
        $stats = $this->transactionService->getStatsForAgent($agentId);
        $customerCount = $this->customerService->listForAgent()->total();
        $wallet = $this->walletService->getForAgent($agentId);

        return view('agent.dashboard', compact('stats', 'customerCount', 'wallet'));
    }
}
