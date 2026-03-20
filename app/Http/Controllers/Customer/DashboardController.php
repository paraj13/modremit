<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $recentTransactions = $customer->transactions()->with('recipient')->latest()->limit(5)->get();
        $totalSent = $customer->transactions()->where('status', 'completed')->sum('chf_amount');
        $pendingTx = $customer->transactions()->whereIn('status', ['pending', 'processing'])->count();
        return view('customer.dashboard', compact('customer', 'recentTransactions', 'totalSent', 'pendingTx'));
    }
}
