<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('agent.dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Create Sumsub Applicant and send KYC email
        try {
            \Illuminate\Support\Facades\Log::info("Initiating Agent KYC for registration: " . $user->id);
            $sumsub = app(\App\Integrations\Sumsub\SumsubKycService::class);
            $applicantId = $sumsub->createAgentApplicant($user);
            
            if ($applicantId) {
                \Illuminate\Support\Facades\Log::info("Sumsub Applicant created: " . $applicantId);
                $user->update(['sumsub_applicant_id' => $applicantId]);
                
                $verificationLink = $sumsub->generateWebSdkLink($applicantId);
                if ($verificationLink) {
                    \Illuminate\Support\Facades\Log::info("Verification link generated: " . $verificationLink);
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerifyAgentKycMail($user, $verificationLink));
                    \Illuminate\Support\Facades\Log::info("VerifyAgentKycMail sent to: " . $user->email);
                } else {
                    \Illuminate\Support\Facades\Log::warning("Failed to generate verification link for: " . $user->email);
                }
            } else {
                \Illuminate\Support\Facades\Log::warning("Failed to create Sumsub applicant for: " . $user->email);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to initiate Agent KYC during registration: " . $user->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('login')->with('success', 'Registration successful. Please check your email for identity verification and await admin approval.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => 'pending',
            'is_active' => false,
        ]);

        $user->assignRole('agent');

        return $user;
    }
}
