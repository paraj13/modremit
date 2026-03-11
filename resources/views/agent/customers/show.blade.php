@extends('layouts.agent')

@section('page_title', 'Customer Profile')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body text-center py-5">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                    <span class="h1 mb-0 fw-bold">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                </div>
                <h4 class="fw-bold mb-1 text-dark">{{ $customer->name }}</h4>
                <p class="text-muted mb-4 small"><i class="bi bi-envelope me-1"></i> {{ $customer->email }}</p>
                
                @php
                    $badgeClass = match($customer->kyc_status) {
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'pending' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                    };
                @endphp
                <div class="mb-4">
                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-shield-check me-1"></i> KYC: {{ ucfirst($customer->kyc_status) }}
                    </span>
                </div>
                
                <div class="d-grid gap-2 pt-2 px-3">
                    <form action="{{ route('agent.customers.refresh-kyc', $customer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm w-100 rounded-pill border py-2">
                             <i class="bi bi-arrow-clockwise me-1"></i> Sync Verification Status
                        </button>
                    </form>
                    <a href="{{ route('agent.customers.edit', $customer->id) }}" class="btn btn-primary btn-sm rounded-pill py-2 shadow-sm">
                         <i class="bi bi-pencil-square me-1"></i> Edit Details
                    </a>
                </div>
            </div>
            <div class="card-footer bg-light border-0 py-3 rounded-bottom-4">
                <div class="d-flex justify-content-between px-3">
                    <span class="small text-muted fw-bold">PHONE</span>
                    <span class="small text-dark fw-bold">{{ $customer->phone }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Beneficiary Recipients</h5>
                <a href="{{ route('agent.recipients.create', ['customer_id' => $customer->id]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                     <i class="bi bi-plus-lg me-1"></i> Add Recipient
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Beneficiary Name</th>
                                <th>Account Details</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->recipients as $recipient)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $recipient->name }}</div>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $recipient->country }}</small>
                                </td>
                                <td>
                                    @if($recipient->upi_id)
                                        <div class="badge bg-info bg-opacity-10 text-info border-info border border-opacity-25 px-2 py-1">
                                            <i class="bi bi-qr-code me-1"></i> UPI: {{ $recipient->upi_id }}
                                        </div>
                                    @else
                                        <div class="text-dark fw-medium small">{{ $recipient->bank_name }}</div>
                                        <div class="text-muted x-small">A/C: {{ $recipient->account_number }}</div>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('agent.recipients.edit', $recipient->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 me-2">Edit</a>
                                        @if($customer->kyc_status === 'approved')
                                            <a href="{{ route('agent.transfers.create', ['customer_id' => $customer->id, 'recipient_id' => $recipient->id]) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                 <i class="bi bi-send-fill me-1 small"></i> Send Money
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted py-4">
                                        <i class="bi bi-people h1 opacity-25"></i>
                                        <p class="mt-2 mb-0">No recipients linked to this customer.</p>
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
</div>
@endsection
