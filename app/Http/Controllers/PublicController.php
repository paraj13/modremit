<?php

namespace App\Http\Controllers;

use App\Services\FxService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function __construct(
        private \App\Services\PublicFxService $publicFxService
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

        $quote = $this->publicFxService->getRate(
            $request->from,
            $request->to,
            $request->amount
        );

        return response()->json($quote);
    }
}
