@extends('layouts.admin')

@section('page_title', 'Agent Management')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Active Agents</h5>
        <a href="{{ route('admin.agents.create') }}" class="btn btn-primary">Create New Agent</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Balance (CHF)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                    <tr>
                        <td class="fw-bold">{{ $agent->name }}</td>
                        <td>{{ $agent->email }}</td>
                        <td>
                            <span class="badge {{ $agent->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $agent->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td>{{ number_format($agent->wallet->chf_balance ?? 0, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.agents.toggle', $agent->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $agent->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    {{ $agent->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">No agents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
