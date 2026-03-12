<?php

namespace App\Http\Controllers;

use App\Services\FxService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function __construct(
        private \App\Services\FxRatesService $fxRatesService
    ) {}

    public function index()
    {
        return view('welcome');
    }

    public function getQuote(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'from'   => 'required|string|size:3',
            'to'     => 'required|string|size:3',
        ]);

        $rateData = $this->fxRatesService->getRate(
            $request->from,
            $request->to
        );

        // Calculate public-facing quote with a standard fee (e.g. 1.5%)
        $amount = (float) $request->amount;
        $feeRate = 0.015;
        $fee = round($amount * $feeRate, 2);
        $netAmount = $amount - $fee;
        $result = round($netAmount * $rateData['rate'], 2);

        $quote = [
            'from' => $rateData['from'],
            'to' => $rateData['to'],
            'amount' => $amount,
            'rate' => $rateData['rate'],
            'fee' => $fee,
            'net_amount' => $netAmount,
            'result' => $result,
            'last_updated' => $rateData['last_updated']
        ];

        return response()->json($quote);
    }
}
