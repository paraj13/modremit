<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TrackTransactionController extends Controller
{
    /**
     * Display the specified transaction tracking page.
     */
    public function show($hash)
    {
        $transaction = Transaction::with(['customer', 'recipient', 'agent'])
            ->where('unique_hash', $hash)
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found.');
        }

        return view('track', compact('transaction'));
    }

    public function receipt($hash)
    {
        $transaction = Transaction::with(['customer', 'recipient', 'agent'])
            ->where('unique_hash', $hash)
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found.');
        }

        return view('customer.transactions.receipt', compact('transaction'));
    }
}
