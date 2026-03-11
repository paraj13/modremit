<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['agent', 'customer', 'recipient'])
            ->latest()
            ->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }
}
