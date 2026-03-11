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
        if ($request->ajax()) {
            $data = User::role('agent')->where('status', 'approved')->select(['id', 'name', 'email', 'phone', 'is_active', 'created_at']);
            return \Yajra\DataTables\Facades\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $class = $row->is_active ? 'bg-success' : 'bg-danger';
                    $text = $row->is_active ? 'Active' : 'Inactive';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.agents.edit', $row->id).'" class="btn btn-sm btn-outline-primary me-1">Edit</a>';
                    $btn .= '<form action="'.route('admin.agents.toggle', $row->id).'" method="POST" class="d-inline">
                                '.csrf_field().'
                                <button type="submit" class="btn btn-sm '.($row->is_active ? 'btn-outline-danger' : 'btn-outline-success').'">
                                    '.($row->is_active ? 'Disable' : 'Enable').'
                                </button>
                             </form>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.agents.index');
    }

    public function pending(Request $request)
    {
        if ($request->ajax()) {
            $data = User::role('agent')->where('status', 'pending')->select(['id', 'name', 'email', 'phone', 'created_at']);
            return \Yajra\DataTables\Facades\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<form action="'.route('admin.agents.approve', $row->id).'" method="POST" class="d-inline me-1">
                                '.csrf_field().'
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                             </form>';
                    $btn .= '<form action="'.route('admin.agents.reject', $row->id).'" method="POST" class="d-inline">
                                '.csrf_field().'
                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                             </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.agents.pending');
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
