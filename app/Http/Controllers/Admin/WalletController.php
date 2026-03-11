<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function index()
    {
        $agents = User::role('agent')->with('wallet')->get();
        return view('admin.wallets.index', compact('agents'));
    }

    public function showCreditForm(User $agent)
    {
        return view('admin.wallets.credit', compact('agent'));
    }

    public function credit(Request $request, User $agent)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $this->walletService->deposit(
            $agent->id, 
            $request->amount, 
            $request->description ?? 'Admin Manual Credit',
            Auth::id()
        );

        return redirect()->route('admin.wallets.index')->with('success', 'Wallet credited successfully.');
    }
}
