@extends('layouts.agent')

@section('page_title', 'Manage Beneficiaries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="table-premium-container">
            <div class="d-flex justify-content-between align-items-center mb-4 px-3">
                <div hollow>
                    <h5 class="mb-0 fw-bold text-brand-dark">My Beneficiaries</h5>
                    <p class="text-muted small mb-0">Manage all recipients linked to your customers</p>
                </div>
                <a href="{{ route('agent.customers.index') }}" class="btn btn-brand btn-sm px-4 rounded-pill">
                    <i class="bi bi-person-plus me-1"></i> View Customers
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle table-premium">
                    <thead>
                        <tr>
                            <th class="ps-3">Status</th>
                            <th>Beneficiary Name</th>
                            <th>Bank / UPI Details</th>
                            <th>Sender (Customer)</th>
                            <th class="text-center">Country</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipients as $recipient)
                            <tr>
                                <td class="ps-3">
                                    <span class="status-pill status-{{ $recipient->status ?? 'approved' }} px-3">
                                        {{ strtoupper($recipient->status ?? 'ACTIVE') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-brand-dark">{{ $recipient->name }}</div>
                                    <div class="small text-muted">{{ $recipient->email ?? 'No email provided' }}</div>
                                </td>
                                <td>
                                    @if($recipient->upi_id)
                                        <div class="badge bg-brand-lime text-brand-dark border-0 px-2 rounded-pill">
                                            <i class="bi bi-lightning-fill me-1"></i> UPI: {{ $recipient->upi_id }}
                                        </div>
                                    @else
                                        <div class="text-brand-dark fw-bold small">{{ $recipient->bank_name }}</div>
                                        <div class="text-muted small font-monospace">AC: {{ $recipient->account_number }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $recipient->customer->name }}</div>
                                    <div class="small text-muted">ID: #{{ $recipient->customer->id }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-3 rounded-pill">{{ $recipient->country }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('agent.recipients.edit', $recipient->id) }}" class="btn btn-outline-dark rounded-pill px-3">
                                            Edit
                                        </a>
                                        @if($recipient->customer->kyc_status === 'approved')
                                            <a href="{{ route('agent.transfers.create', ['customer_id' => $recipient->customer_id, 'recipient_id' => $recipient->id]) }}" class="btn btn-sm btn-brand rounded-pill px-3">
                                                <i class="bi bi-send-fill me-1"></i> Transfer
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-people h1 opacity-25"></i>
                                        <p class="mt-2 mb-0">No beneficiaries found yet.</p>
                                        <a href="{{ route('agent.customers.index') }}" class="btn btn-link text-brand-dark fw-bold">Select a customer to add recipients</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($recipients instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-4 px-3">
                    {{ $recipients->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
