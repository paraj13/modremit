<?php

namespace App\Http\Controllers;

use App\Integrations\Sumsub\SumsubKycService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SumsubWebhookController extends Controller
{
    public function __construct(private SumsubKycService $kycService) {}

    public function handle(Request $request)
    {
        $payload = $request->all();
        
        Log::info('Sumsub Webhook received', ['payload' => $payload]);

        // Basic verification of the webhook (Sumsub sends a signature, but for minimal implementation we'll skip for now or use a basic token)
        // In production, we should verify X-App-Access-Sig
        
        try {
            $this->kycService->handleWebhook($payload);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Sumsub Webhook error', ['message' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
