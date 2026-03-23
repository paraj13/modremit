@extends('layouts.admin')

@section('page_title', 'Pending Agent Applications')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('admin.agents.index') }}" class="btn btn-brand-outline">
            <i class="bi bi-arrow-left me-2"></i> Back to Agents
        </a>
    </div>
</div>

<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Agent Registration Requests</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Applied Date</th>
                    <th width="220px" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $index => $agent)
                <tr>
                    <td>{{ $agents->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $agent->name }}</td>
                    <td>{{ $agent->email }}</td>
                    <td>{{ $agent->phone ?? 'N/A' }}</td>
                    <td>{{ $agent->created_at->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <form action="{{ route('admin.agents.approve', $agent->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-brand px-3">Approve</button>
                            </form>
                            <form action="{{ route('admin.agents.reject', $agent->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-md btn-outline-danger px-3 rounded-pill">Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">No pending applications found.</td>
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
