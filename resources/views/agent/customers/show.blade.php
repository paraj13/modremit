@extends('layouts.agent')

@section('page_title', 'Customer Profile')

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
                    <div class="d-flex justify-content-between border-bottom pb-2">
                        <span class="small text-muted fw-bold">PHONE</span>
                        <span class="small text-brand-dark fw-bold">{{ $customer->phone }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <form action="{{ route('agent.customers.refresh-kyc', $customer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-brand-outline btn-sm w-100 py-2">
                             <i class="bi bi-arrow-clockwise me-1"></i> Sync KYC
                        </button>
                    </form>
                    <a href="{{ route('agent.customers.edit', $customer->id) }}" class="btn btn-brand btn-sm py-2">
                         <i class="bi bi-pencil-square me-1"></i> Edit Profile
                    </a>
                    
                    <form action="{{ route('agent.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100 py-2">
                            <i class="bi bi-trash me-1"></i> Delete Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-premium-container h-100">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h5 class="mb-0 fw-bold text-brand-dark">Beneficiary Recipients</h5>
                <a href="{{ route('agent.recipients.create', ['eid' => \Illuminate\Support\Facades\Crypt::encryptString($customer->id)]) }}" class="btn btn-brand btn-sm px-4">
                     <i class="bi bi-plus-lg me-1"></i> Add New
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-premium">
                    <thead>
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Details</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->recipients as $recipient)
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold text-brand-dark">{{ $recipient->name }}</div>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $recipient->country }}</small>
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
                                <div class="btn-group">
                                    <a href="{{ route('agent.recipients.edit', $recipient->id) }}" class="btn btn-sm btn-outline-dark border rounded-pill pt-2 px-3 me-2">Edit</a>
                                    @if($customer->kyc_status === 'approved')
                                        <a href="{{ route('agent.transfers.create', ['customer_id' => $customer->id, 'recipient_id' => $recipient->id, 'eid' => \Illuminate\Support\Facades\Crypt::encryptString($customer->id), 'return_to' => request('return_to')]) }}" class="btn btn-sm btn-brand rounded-pill px-3">
                                             <i class="bi bi-send-fill me-1"></i> Send
                                        </a>
                                    @endif
                                </div>
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

{{-- Transaction History for this Customer --}}
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0 text-brand-dark"><i class="bi bi-clock-history text-muted me-2"></i> Transaction History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3 small text-muted text-uppercase">Date</th>
                                <th class="border-0 py-3 small text-muted text-uppercase">Recipient</th>
                                <th class="border-0 py-3 small text-muted text-uppercase">Amount (CHF)</th>
                                <th class="border-0 py-3 small text-muted text-uppercase">Target</th>
                                <th class="border-0 py-3 small text-muted text-uppercase text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->transactions as $txn)
                            <tr>
                                <td class="px-4 py-3 small">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                                <td class="fw-bold text-brand-dark">{{ $txn->recipient->name ?? 'N/A' }}</td>
                                <td class="fw-bold">CHF {{ number_format($txn->chf_amount, 2) }}</td>
                                <td class="text-success small fw-bold">{{ number_format($txn->target_amount, 2) }} {{ $txn->target_currency }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $txn->status_badge }} rounded-pill px-3">{{ strtoupper($txn->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">No transactions found for this customer.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
