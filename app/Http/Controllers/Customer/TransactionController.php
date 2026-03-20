<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $transactions = $customer->transactions()->with('recipient')->latest()->paginate(10);
        return view('customer.transactions.index', compact('transactions'));
    }

    public function show(int $id)
    {
        $customer = Auth::guard('customer')->user();
        $transaction = $customer->transactions()->with('recipient')->findOrFail($id);
        return view('customer.transactions.show', compact('transaction'));
    }

    public function receipt(int $id)
    {
        $customer = Auth::guard('customer')->user();
        $transaction = $customer->transactions()->with('recipient')->findOrFail($id);
        return view('customer.transactions.receipt', compact('transaction'));
    }
}
