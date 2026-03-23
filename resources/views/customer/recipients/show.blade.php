@extends('layouts.customer')

@section('page_title', 'Recipient Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-brand-dark py-4 px-4 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-brand-lime rounded-circle p-2 me-3 d-flex align-items-center justify-content-center icon-circle-lg">
                        <i class="bi bi-person-fill text-brand-dark h4 mb-0"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">{{ $recipient->name }}</h5>
                        <p class="text-brand-lime small mb-0 opacity-75">Saved Beneficiary</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1 d-block">BANK NAME</label>
                        <h5 class="fw-bold text-brand-dark">{{ $recipient->bank_name }}</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1 d-block">COUNTRY</label>
                        <h5 class="fw-bold text-brand-dark">
                            <span class="fi fi-{{ strtolower(\App\Constants\CountryCurrency::getFlagByCountry($recipient->country)) }} me-1"></span>
                            {{ $recipient->country }}
                        </h5>
                    </div>
                    <div class="col-md-12">
                        <hr class="opacity-10">
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1 d-block">ACCOUNT NUMBER / IBAN</label>
                        <h5 class="fw-bold text-brand-dark">{{ $recipient->account_number }}</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold mb-1 d-block">IFSC / SWIFT CODE</label>
                        <h5 class="fw-bold text-brand-dark">{{ $recipient->ifsc_code ?? '—' }}</h5>
                    </div>
                    @if($recipient->upi_id)
                    <div class="col-md-12">
                        <label class="text-muted small fw-bold mb-1 d-block">UPI ID</label>
                        <h5 class="fw-bold text-brand-dark">{{ $recipient->upi_id }}</h5>
                    </div>
                    @endif
                </div>

                <div class="mt-5 pt-3 d-flex gap-3">
                    <a href="{{ route('customer.recipients.index') }}" class="btn btn-outline-dark px-4 py-2 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Back to Recipients
                    </a>
                    <a href="{{ route('customer.transfers.create', ['recipient_id' => $recipient->id]) }}" class="btn btn-brand px-4 py-2 rounded-pill">
                        <i class="bi bi-send-fill me-1"></i> Send Money Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction History for this Recipient --}}
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-white py-3 px-4 border-0">
                <h6 class="fw-bold text-brand-dark mb-0">Transaction History with {{ $recipient->name }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-premium mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 small fw-bold text-muted">DATE</th>
                            <th class="border-0 py-3 small fw-bold text-muted">SENT AMOUNT</th>
                            <th class="border-0 py-3 small fw-bold text-muted">RECEIVED AMOUNT</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-center">STATUS</th>
                            <th class="border-0 py-3 small fw-bold text-muted text-center pe-4">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipient->transactions as $t)
                        <tr>
                            <td class="px-4 py-3">
                                <span class="small fw-bold text-brand-dark d-block">#{{ $t->id }}</span>
                                <span class="x-small text-muted">{{ $t->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <span class="small fw-bold">{{ number_format($t->chf_amount, 2) }} CHF</span>
                            </td>
                            <td>
                                <span class="small fw-bold text-success">{{ number_format($t->target_amount, 2) }} {{ $t->target_currency }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $t->status === 'completed' ? 'success' : ($t->status === 'pending' ? 'warning' : 'info') }} rounded-pill px-3 x-small">
                                    {{ strtoupper($t->status) }}
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <a href="{{ route('customer.transactions.show', $t->id) }}" class="btn btn-sm btn-brand rounded-pill px-3 shadow-sm">Details</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted small mb-0">No transaction history found for this recipient.</p>
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
