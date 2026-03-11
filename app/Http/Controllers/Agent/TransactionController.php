<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService) {}

    public function index(Request $request)
    {
        $transactions = $this->transactionService->listForAgent($request->all());
        return view('agent.transactions.index', compact('transactions'));
    }

    public function show(int $id)
    {
        $transaction = $this->transactionService->find($id);
        if (!$transaction || $transaction->agent_id !== auth()->id()) abort(404);
        return view('agent.transactions.show', compact('transaction'));
    }
}
