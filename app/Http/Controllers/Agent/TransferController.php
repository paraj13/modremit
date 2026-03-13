<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Services\CustomerService;
use App\Services\FxService;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function __construct(
        private TransactionService $transactionService,
        private CustomerService $customerService,
        private FxService $fxService
    ) {}

    public function create(Request $request)
    {
        $customers = $this->customerService->listForAgent()->where('kyc_status', 'approved');
        $selectedCustomer = null;
        if ($request->customer_id) {
            $selectedCustomer = $this->customerService->findOwned($request->customer_id);
        }
        return view('agent.transfers.create', compact('customers', 'selectedCustomer'));
    }

    public function getQuote(Request $request)
    {
        $request->validate([
            'chf_amount'      => 'required|numeric|min:1',
            'target_currency' => 'required|string|size:3',
        ]);

        $quote = $this->fxService->fetchAndStoreQuote(
            $request->chf_amount, 
            auth()->id(), 
            $request->target_currency
        );

        return response()->json($quote);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'   => 'required|integer',
            'recipient_id'  => 'required|integer',
            'chf_amount'      => 'required|numeric|min:1',
            'target_currency' => 'required|string|size:3',
            'quote_id'        => 'nullable|integer',
            'notes'           => 'nullable|string',
        ]);

        try {
            $transaction = $this->transactionService->initiateTransfer($data);
            return redirect()->route('agent.transactions.show', $transaction->id)
                ->with('success', 'Transfer completed successfully.');
        } catch (\App\Exceptions\InsufficientWalletBalanceException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }
}
