@extends('layouts.agent')

@section('page_title', 'Customer Management')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-end">
        <a href="{{ route('agent.customers.create') }}" class="btn btn-brand">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Customer
        </a>
    </div>
</div>

<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">My Registered Customers</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>KYC Status</th>
                    <th>Recipients</th>
                    <th width="240px" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $customers->firstItem() + $index }}</td>
                    <td class="fw-bold text-brand-dark">{{ $customer->name }}</td>
                    <td>
                        <div>{{ $customer->email }}</div>
                        <small class="text-muted">{{ $customer->phone ?? 'N/A' }}</small>
                    </td>
                    <td>
                        @php
                            $badgeClass = match($customer->kyc_status) {
                                'approved' => 'bg-brand-mint text-brand-dark',
                                'rejected' => 'bg-danger text-white',
                                'pending'  => 'bg-warning text-dark',
                                default    => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} px-3">
                            {{ strtoupper($customer->kyc_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-3">
                            {{ $customer->recipients_count ?? 0 }} Recipients
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('agent.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">View</a>
                            <a href="{{ route('agent.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">Edit</a>
                            <form action="{{ route('agent.customers.refresh-kyc', $customer->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-dark rounded-3 px-3">Refresh</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 px-3">
        {{ $customers->links() }}
    </div>
</div>
@endsection
