<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function required()
    {
        $customer = Auth::guard('customer')->user();
        
        // Try to update status from Sumsub if not already approved
        if ($customer->sumsub_applicant_id && $customer->kyc_status !== 'approved') {
            try {
                $kyc = app(\App\Integrations\Sumsub\SumsubKycService::class);
                $status = $kyc->getStatus($customer->sumsub_applicant_id);
                
                if ($status !== $customer->kyc_status) {
                    $customer->update(['kyc_status' => $status]);
                    if ($status === 'approved') {
                        return redirect()->route('customer.dashboard')->with('success', 'Your KYC has been approved!');
                    }
                }
            } catch (\Exception $e) {
                logger()->warning('Failed to auto-check Sumsub status', ['error' => $e->getMessage()]);
            }
        }

        if  ($customer->kyc_status === 'approved') {
            return redirect()->route('customer.dashboard');
        }

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
