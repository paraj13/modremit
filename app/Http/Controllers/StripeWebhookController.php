<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;

class StripeWebhookController extends Controller
{
    public function handle(Request $request, StripeService $stripeService)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        if ($stripeService->handleWebhook($payload, $sigHeader)) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
