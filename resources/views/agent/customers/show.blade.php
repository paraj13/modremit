@extends('layouts.agent')

@section('page_title', 'Customer Profile')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="h2 mb-0">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                </div>
                <h4 class="fw-bold mb-1">{{ $customer->name }}</h4>
                <p class="text-muted mb-3">{{ $customer->email }}</p>
                
                @php
                    $badgeClass = match($customer->kyc_status) {
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'pending' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                    };
                @endphp
                <span class="badge {{ $badgeClass }} px-3 py-2 mb-3">KYC: {{ ucfirst($customer->kyc_status) }}</span>
                
                <div class="d-grid gap-2 mt-2">
                    <form action="{{ route('agent.customers.refresh-kyc', $customer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm w-100">Sync with Sumsub</button>
                    </form>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between small text-muted">
                    <span>Phone:</span>
                    <span class="text-dark">{{ $customer->phone }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recipients</h5>
                <a href="{{ route('agent.recipients.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary btn-sm">Add Recipient</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Bank Details</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->recipients as $recipient)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $recipient->name }}</div>
                                    <small class="text-muted">{{ $recipient->country }}</small>
                                </td>
                                <td>
                                    @if($recipient->upi_id)
                                        <div class="badge bg-light text-dark">UPI: {{ $recipient->upi_id }}</div>
                                    @else
                                        <div>{{ $recipient->bank_name }}</div>
                                        <small class="text-muted">A/C: {{ $recipient->account_number }}</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('agent.recipients.edit', $recipient->id) }}" class="btn btn-sm btn-link text-primary">Edit</a>
                                    @if($customer->kyc_status === 'approved')
                                        <a href="{{ route('agent.transfers.create', ['customer_id' => $customer->id, 'recipient_id' => $recipient->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Send Money</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <p class="text-muted mb-0">No recipients linked to this customer.</p>
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
