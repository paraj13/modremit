<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct(private CustomerService $customerService) {}

    public function showRegistrationForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:customers,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create customer with null agent_id (self-registered)
        $customer = Customer::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'password'   => Hash::make($data['password']),
            'agent_id'   => null,
            'kyc_status' => 'pending',
        ]);

        // Trigger Sumsub KYC applicant creation + send verification email
        try {
            $kyc = app(\App\Integrations\Sumsub\SumsubKycService::class);
            $applicantId = $kyc->createApplicant($customer);
            if ($applicantId) {
                $customer->update([
                    'sumsub_applicant_id' => $applicantId,
                    'kyc_status'          => 'pending',
                ]);
                $verificationLink = $kyc->generateWebSdkLink($applicantId);
                if ($verificationLink) {
                    \Illuminate\Support\Facades\Mail::to($customer->email)
                        ->send(new \App\Mail\VerifyKycMail($customer, $verificationLink));
                }
            }
        } catch (\Exception $e) {
            logger()->warning('KYC setup failed for self-registered customer', ['error' => $e->getMessage()]);
        }

        Auth::guard('customer')->login($customer);

        return redirect()->route('customer.kyc.required')
            ->with('success', 'Registration successful! Please complete your KYC verification.');
    }
}
