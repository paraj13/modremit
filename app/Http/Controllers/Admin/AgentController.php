<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            ->where('status', 'pending')
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
            'email'    => 'required|email|unique:users,email',
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

        try {
            $sumsub = app(\App\Integrations\Sumsub\SumsubKycService::class);
            $applicantId = $sumsub->createAgentApplicant($user);
            if ($applicantId) {
                $user->update(['sumsub_applicant_id' => $applicantId]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to create sumsub for agent " . $user->id, ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.agents.index')->with('success', 'Agent created successfully. Default password is 12345678.');
    }

    public function show(User $agent)
    {
        $agent->load(['wallet', 'wallet.transactions' => function($q) {
            $q->latest()->limit(50);
        }]);

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
            'email' => 'required|email|unique:users,email,' . $agent->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $agent->update($request->only('name', 'email', 'phone'));

        if ($request->password) {
            $request->validate(['password' => 'confirmed|min:8']);
            $agent->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.agents.index')->with('success', 'Agent updated.');
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
