<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        $agents = User::role('agent')->paginate(20);
        return view('admin.agents.index', compact('agents'));
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
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('agent');

        return redirect()->route('admin.agents.index')->with('success', 'Agent created successfully.');
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
}
