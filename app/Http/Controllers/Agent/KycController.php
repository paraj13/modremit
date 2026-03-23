<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function required()
    {
        $agent = Auth::user();
        
        // Try to update status from Sumsub if not already approved
        if ($agent->sumsub_applicant_id && $agent->kyc_status !== 'approved') {
            try {
                $kyc = app(\App\Integrations\Sumsub\SumsubKycService::class);
                $status = $kyc->getStatus($agent->sumsub_applicant_id);
                
                if ($status !== $agent->kyc_status) {
                    $agent->update(['kyc_status' => $status]);
                    if ($status === 'approved') {
                        return redirect()->route('agent.dashboard')->with('success', 'Your KYC has been approved!');
                    }
                }
            } catch (\Exception $e) {
                logger()->warning('Failed to auto-check Sumsub status for agent', ['error' => $e->getMessage()]);
            }
        }

        // Generate a fresh WebSDK link for the agent to continue KYC
        $verificationLink = null;
        if ($agent->sumsub_applicant_id) {
            try {
                $kyc = app(\App\Integrations\Sumsub\SumsubKycService::class);
                $verificationLink = $kyc->generateWebSdkLink($agent->sumsub_applicant_id);
            } catch (\Exception $e) {
                logger()->warning('Could not generate WebSDK link for agent', ['error' => $e->getMessage()]);
            }
        }
        
        return view('agent.kyc-required', compact('agent', 'verificationLink'));
    }

    public function awaitingApproval()
    {
        $agent = Auth::user();
        if ($agent->isKycApproved() && $agent->status === 'approved') {
            return redirect()->route('agent.dashboard');
        }
        return view('agent.awaiting-approval', compact('agent'));
    }
}
