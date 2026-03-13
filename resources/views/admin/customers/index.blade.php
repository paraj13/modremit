@extends('layouts.admin')

@section('page_title', 'All Customers')

@section('content')
<div class="table-premium-container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h5 class="mb-0 fw-bold text-brand-dark">Registered Platform Customers</h5>
        <div class="search-box">
            <input type="text" class="form-control rounded-pill px-4 shadow-sm border-0 bg-white" placeholder="Search customers..." data-search-target="#customersTable" style="min-width: 280px;">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-premium" id="customersTable">
            <thead>
                <tr>
                    <th width="50px">No</th>
                    <th>Customer Name</th>
                    <th>Managing Agent</th>
                    <th>Contact</th>
                    <th>KYC Status</th>
                    <th>Recipients</th>
                    <th width="80px" class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $customers->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $customer->name }}</td>
                    <td><span class="badge bg-light text-brand-dark border">{{ $customer->agent->name ?? 'N/A' }}</span></td>
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
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No customers found.</td>
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
