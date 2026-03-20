<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function required()
    {
        $customer = Auth::guard('customer')->user();
        
        // Generate a fresh WebSDK link for the customer to continue KYC
        $verificationLink = null;
        if ($customer->sumsub_applicant_id) {
            try {
                $kyc = app(\App\Integrations\Sumsub\SumsubKycService::class);
                $verificationLink = $kyc->generateWebSdkLink($customer->sumsub_applicant_id);
            } catch (\Exception $e) {
                logger()->warning('Could not generate WebSDK link', ['error' => $e->getMessage()]);
            }
        }
        
        return view('customer.kyc-required', compact('customer', 'verificationLink'));
    }
}
