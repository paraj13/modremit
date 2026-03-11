@extends('layouts.agent')

@section('page_title', 'Customers')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Customer Management</h5>
        <a href="{{ route('agent.customers.create') }}" class="btn btn-primary">Add New Customer</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>KYC Status</th>
                        <th>Recipients</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $customer->name }}</div>
                            <small class="text-muted">Added {{ $customer->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <div>{{ $customer->email }}</div>
                            <small class="text-muted">{{ $customer->phone }}</small>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($customer->kyc_status) {
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'pending' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($customer->kyc_status) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $customer->recipients_count ?? $customer->recipients->count() }} Recipients</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('agent.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('agent.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('agent.customers.refresh-kyc', $customer->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Refresh KYC</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <p class="text-muted mb-0">No customers found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
