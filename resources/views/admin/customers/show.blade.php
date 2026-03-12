@extends('layouts.admin')

@section('page_title', 'Customer Details')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-premium p-0 h-100 overflow-hidden">
            <div class="bg-brand-dark py-5 text-center">
                <div class="bg-brand-lime text-brand-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-lg" style="width: 80px; height: 80px;">
                    <span class="h2 mb-0 fw-800">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                </div>
                <h4 class="fw-bold mb-1 text-white">{{ $customer->name }}</h4>
                <p class="text-brand-lime small mb-0 opacity-75">{{ $customer->email }}</p>
            </div>
            <div class="p-4">
                @php
                    $badgeClass = match($customer->kyc_status) {
                        'approved' => 'bg-brand-mint text-brand-dark',
                        'rejected' => 'bg-danger text-white',
                        'pending' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                    };
                @endphp
                <div class="text-center mb-4">
                    <span class="badge {{ $badgeClass }} px-4 py-2 rounded-pill shadow-sm fw-bold">
                        <i class="bi bi-shield-check me-1"></i> {{ strtoupper($customer->kyc_status) }}
                    </span>
                </div>
                
                <div class="space-y-3 mb-4">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="small text-muted fw-bold">PHONE</span>
                        <span class="small text-brand-dark fw-bold">{{ $customer->phone }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2">
                        <span class="small text-muted fw-bold">AGENT</span>
                        <span class="small text-primary fw-bold">{{ $customer->agent->name }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-brand-outline btn-sm py-2">
                         <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-premium-container h-100">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h5 class="mb-0 fw-bold text-brand-dark">Beneficiary Recipients ({{ $customer->recipients->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-premium">
                    <thead>
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Details</th>
                            <th class="text-end pe-3">Country</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->recipients as $recipient)
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold text-brand-dark">{{ $recipient->name }}</div>
                            </td>
                            <td>
                                @if($recipient->upi_id)
                                    <span class="badge bg-brand-lime text-brand-dark border-0 px-2">UPI: {{ $recipient->upi_id }}</span>
                                @else
                                    <div class="text-brand-dark fw-bold small">{{ $recipient->bank_name }}</div>
                                    <div class="text-muted small">A/C: {{ $recipient->account_number }}</div>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <span class="badge bg-light text-dark border px-3 rounded-pill">{{ $recipient->country }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-people h1 opacity-25"></i>
                                    <p class="mt-2 mb-0">No recipients linked yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
