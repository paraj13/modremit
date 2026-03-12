<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(
        private WalletService $walletService,
        private StripeService $stripeService
    ) {}

    public function index(Request $request)
    {
        $agentId = Auth::id();
        $wallet = $this->walletService->getForAgent($agentId);
        $transactions = $this->walletService->getHistory($agentId);

        // Fallback: If webhook hasn't arrived yet, check session status manually
        if ($request->filled('session_id') && $request->query('status') === 'success') {
            try {
                $session = $this->stripeService->retrieveSession($request->session_id);
                if ($session->payment_status === 'paid') {
                    $this->stripeService->processSuccessfulPayment($session);
                    // Refresh data after potential credit
                    $wallet = $wallet->fresh();
                    $transactions = $this->walletService->getHistory($agentId);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Stripe Fallback Error: ' . $e->getMessage());
            }
        }

        if ($request->query('status') === 'success') {
            session()->now('success', 'Payment successful! Your wallet balance has been updated.');
        } elseif ($request->query('status') === 'cancel') {
            session()->now('error', 'Payment was cancelled.');
        }

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

    public function checkout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $session = $this->stripeService->createCheckoutSession(Auth::id(), $request->amount);

        return redirect($session->url);
    }
}
