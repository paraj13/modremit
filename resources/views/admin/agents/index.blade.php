@extends('layouts.admin')

@section('page_title', 'Agent Management')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-end">
        <a href="{{ route('admin.agents.create') }}" class="btn btn-brand">
            <i class="bi bi-person-plus me-2"></i> Create New Agent
        </a>
    </div>
</div>

<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Active Platform Agents</h5>
        <div class="search-box">
            <input type="text" class="form-control rounded-pill px-4 shadow-sm border-0 bg-white" placeholder="Search agents..." data-search-target="#agentsTable" style="min-width: 280px;">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium" id="agentsTable">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th width="200px" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $index => $agent)
                <tr>
                    <td>{{ $agents->firstItem() + $index }}</td>
                    <td class="fw-bold">
                        <a href="{{ route('admin.agents.show', $agent->id) }}" class="text-brand-dark text-decoration-none">
                            {{ $agent->name }}
                        </a>
                    </td>
                    <td>{{ $agent->email }}</td>
                    <td>{{ $agent->phone ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $agent->is_active ? 'bg-brand-mint text-brand-dark' : 'bg-danger text-white' }} px-3">
                            {{ $agent->is_active ? 'ACTIVE' : 'INACTIVE' }}
                        </span>
                    </td>
                    <td>{{ $agent->created_at->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3">View</a>
                            <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">Edit</a>
                            <form action="{{ route('admin.agents.toggle', $agent->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $agent->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-3 px-3">
                                    {{ $agent->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No agents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 px-3">
        {{ $agents->links() }}
    </div>
</div>
@endsection
