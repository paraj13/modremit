<?php

namespace App\Http\Controllers;

use App\Services\FxService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function __construct(
        private \App\Services\FxService $fxService
    ) {}

    public function index()
    {
        // Fetch base rate for display (1 CHF to default target INR)
        $fxQuote = $this->fxService->fetchAndStoreQuote(1, null, 'INR');
        $baseRate = $fxQuote->rate;

        return view('welcome', compact('baseRate'));
    }

    public function getQuote(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'from'   => 'required|string|size:3',
            'to'     => 'required|string|size:3',
        ]);

        $fxQuote = $this->fxService->fetchAndStoreQuote((float) $request->amount, null, $request->to);
        
        return response()->json([
            'from'          => $fxQuote->from_currency,
            'to'            => $fxQuote->to_currency,
            'amount'        => $fxQuote->chf_amount,
            'rate'          => $fxQuote->rate,
            'fee'           => $fxQuote->fee,
            'send_amount'   => $fxQuote->send_amount,
            'target_amount' => $fxQuote->target_amount,
            'id'            => $fxQuote->id,
            'last_updated'  => $fxQuote->created_at->toDateTimeString()
        ]);
    }
}
