<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function index()
    {
        $agentId = Auth::id();
        $wallet = $this->walletService->getForAgent($agentId);
        $transactions = $this->walletService->getHistory($agentId);

        return view('agent.wallet.index', compact('wallet', 'transactions'));
    }

    /**
     * Optional: Agents could request a top-up
     * For now, it's just a view showing instructions or a simple form
     */
    public function topUp()
    {
        return view('agent.wallet.topup');
    }
}
