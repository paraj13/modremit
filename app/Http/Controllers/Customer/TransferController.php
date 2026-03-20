<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Services\FxService;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function __construct(
        private TransactionService $transactionService,
        private FxService $fxService
    ) {}

    public function create(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $recipients = $customer->recipients()->get();
        return view('customer.transfers.create', compact('customer', 'recipients'));
    }

    public function getQuote(Request $request)
    {
        $request->validate([
            'chf_amount'      => 'required|numeric|min:1',
            'target_currency' => 'required|string|size:3',
        ]);

        $customer = Auth::guard('customer')->user();

        $fxQuote = $this->fxService->fetchAndStoreQuote(
            $request->chf_amount,
            null, // no agent for customer transfers
            $request->target_currency,
            'CHF' // from_currency defaults to CHF
        );

        return response()->json([
            'from'          => $fxQuote->from_currency,
            'to'            => $fxQuote->to_currency,
            'amount'        => $fxQuote->chf_amount,
            'rate'          => $fxQuote->rate,
            'fee'           => $fxQuote->fee,
            'send_amount'   => $fxQuote->send_amount,
            'target_amount' => $fxQuote->target_amount,
            'id'            => $fxQuote->id,
            'quote_id'      => $fxQuote->quote_id,
            'last_updated'  => $fxQuote->created_at->toDateTimeString()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'recipient_id'    => 'required|integer',
            'chf_amount'      => 'required|numeric|min:1',
            'target_currency' => 'required|string|size:3',
            'quote_id'        => 'nullable|integer',
            'notes'           => 'nullable|string',
        ]);

        $customer = Auth::guard('customer')->user();
        $data['customer_id']   = $customer->id;
        $data['initiated_by']  = 'customer';

        try {
            $transaction = $this->transactionService->initiateCustomerTransfer($data);
            return redirect()->route('customer.transactions.show', $transaction->id)
                ->with('success', 'Transfer submitted successfully!');
        } catch (\App\Exceptions\InsufficientWalletBalanceException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }
}
