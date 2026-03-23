<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgentWelcomeMail;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $agents = User::role('agent')
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('admin.agents.index', compact('agents'));
    }
    
    public function pending(Request $request)
    {
        $agents = User::role('agent')
            ->whereIn('status', ['pending','rejected'])
            ->latest()
            ->paginate(10);

        return view('admin.agents.pending', compact('agents'));
    }

    public function approve($id)
    {
        $agent = User::findOrFail($id);
        $agent->update(['status' => 'approved', 'is_active' => true]);
        return back()->with('success', 'Agent approved successfully.');
    }

    public function reject($id)
    {
        $agent = User::findOrFail($id);
        $agent->update(['status' => 'rejected', 'is_active' => false]);
        return back()->with('success', 'Agent application rejected.');
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make('12345678'),
            'kyc_status' => 'pending',
        ]);

        $user->assignRole('agent');

        // Send Welcome Mail
        try {
            Mail::to($user->email)->send(new AgentWelcomeMail($user, '12345678'));
        } catch (\Exception $e) {
            Log::error("Failed to send welcome mail to agent " . $user->id, ['error' => $e->getMessage()]);
        }

        try {
            Log::info("Initiating Admin-created Agent KYC: " . $user->id);
            $sumsub = app(\App\Integrations\Sumsub\SumsubKycService::class);
            $applicantId = $sumsub->createAgentApplicant($user);
            if ($applicantId) {
                Log::info("Sumsub Applicant created: " . $applicantId);
                $user->update(['sumsub_applicant_id' => $applicantId]);
                
                // Generate and send KYC link
                $verificationLink = $sumsub->generateWebSdkLink($applicantId);
                if ($verificationLink) {
                    Log::info("Verification link generated: " . $verificationLink);
                    Mail::to($user->email)->send(new \App\Mail\VerifyAgentKycMail($user, $verificationLink));
                    Log::info("VerifyAgentKycMail sent to: " . $user->email);
                } else {
                    Log::warning("Failed to generate verification link for admin-created agent: " . $user->email);
                }
            } else {
                Log::warning("Failed to create Sumsub applicant for admin-created agent: " . $user->email);
            }
        } catch (\Exception $e) {
            Log::error("Failed to create sumsub for agent " . $user->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('admin.agents.index')->with('success', 'Agent created successfully. Default password is 12345678.');
    }

    public function show(User $agent)
    {
        $agent->load(['wallet', 'wallet.transactions' => function($q) {
            $q->latest()->limit(50);
        }, 'customers']);

        $stats = [
            'total_added' => $agent->wallet ? $agent->wallet->transactions()->where('type', 'deposit')->where('status', 'completed')->sum('amount') : 0,
            'total_sent'  => $agent->wallet ? abs($agent->wallet->transactions()->where('type', 'transfer')->where('status', 'completed')->sum('amount')) : 0,
        ];

        return view('admin.agents.show', compact('agent', 'stats'));
    }

    public function edit(User $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, User $agent)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $agent->id . ',id,deleted_at,NULL',
            'phone' => 'nullable|string|max:20',
        ]);

        $agent->update($request->only('name', 'email', 'phone'));

        if ($request->password) {
            $request->validate(['password' => 'confirmed|min:8']);
            $agent->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.agents.index')->with('success', 'Agent updated.');
    }

    public function refreshKyc(User $agent)
    {
        try {
            $sumsub = app(\App\Integrations\Sumsub\SumsubKycService::class);
            
            // If missing applicant ID, try to discover it by externalUserId
            if (!$agent->sumsub_applicant_id) {
                $externalId = 'agent_' . $agent->id;
                $discoveredId = $sumsub->getApplicantIdByExternalId($externalId);
                if ($discoveredId) {
                    $agent->update(['sumsub_applicant_id' => $discoveredId]);
                    $agent->refresh();
                    logger()->info("Recovered missing Sumsub ID for agent during sync", ['agent_id' => $agent->id, 'applicant_id' => $discoveredId]);
                } else {
                    return back()->with('warning', 'No Sumsub applicant found for this agent.');
                }
            }

            $status = $sumsub->getStatus($agent->sumsub_applicant_id);
            if ($status) {
                $agent->update(['kyc_status' => $status]);
            }
            return back()->with('success', 'KYC Status synced: ' . ucfirst($status ?? 'pending'));
        } catch (\Exception $e) {
            Log::error("Failed to sync KYC for agent " . $agent->id, ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to sync KYC status.');
        }
    }

    public function toggleStatus(User $agent)
    {
        $agent->update(['is_active' => !$agent->is_active]);
        return back()->with('success', 'Agent status updated.');
    }

    public function destroy(User $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent deleted successfully.');
    }
}
