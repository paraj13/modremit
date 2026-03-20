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
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="small text-muted fw-bold">AGENT</span>
                        <span class="small text-primary fw-bold">{{ $customer->agent->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2">
                        <span class="small text-muted fw-bold">SUMSUB ID</span>
                        <span class="small text-secondary font-monospace">{{ $customer->sumsub_applicant_id ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-brand btn-sm w-100 py-2">
                        <i class="bi bi-pencil-square me-1"></i> Edit Profile
                    </a>

                    <form action="{{ route('admin.customers.refresh-kyc', $customer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-brand-outline btn-sm w-100 py-2">
                             <i class="bi bi-arrow-clockwise me-1"></i> Sync KYC
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100 py-2">
                            <i class="bi bi-trash me-1"></i> Delete Profile
                        </button>
                    </form>

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

<!-- Middle Row: Full Width Images Array -->
@php 
    $sumsub = app(\App\Integrations\Sumsub\SumsubKycService::class);
    $kycData = $sumsub->getApplicantData($customer->sumsub_applicant_id ?? 'dummy_applicant_missing');
@endphp

<div class="row g-4 mt-2 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-vcard text-muted me-2"></i> KYC Identity Verification</h6>
            </div>
            <div class="card-body bg-light">
                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <div class="text-muted small fw-bold mb-3 text-uppercase">ID Document (Front)</div>
                        @if($kycData['doc_front'])
                            <img src="{{ $kycData['doc_front'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" style="max-height: 250px; object-fit: contain;" alt="Doc Front">
                        @else
                            <div class="bg-white border rounded py-5 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-100" style="min-height: 200px;">
                                <i class="bi bi-file-earmark-image fs-1 text-light-emphasis mb-2"></i>
                                Not available
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-muted small fw-bold mb-3 text-uppercase">ID Document (Back)</div>
                        @if($kycData['doc_back'])
                            <img src="{{ $kycData['doc_back'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" style="max-height: 250px; object-fit: contain;" alt="Doc Back">
                        @else
                            <div class="bg-white border rounded py-5 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-100" style="min-height: 200px;">
                                <i class="bi bi-file-earmark-image fs-1 text-light-emphasis mb-2"></i>
                                Not available
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-muted small fw-bold mb-3 text-uppercase">Selfie Match</div>
                        @if($kycData['selfie'])
                            <img src="{{ $kycData['selfie'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" style="max-height: 250px; object-fit: contain;" alt="Selfie">
                        @else
                            <div class="bg-white border rounded py-5 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-100" style="min-height: 200px;">
                                <i class="bi bi-person-bounding-box fs-1 text-light-emphasis mb-2"></i>
                                Not available
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Transaction History for this Customer --}}
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-brand-dark"><i class="bi bi-clock-history text-muted me-2"></i> Transaction History</h5>
                <span class="badge bg-light text-dark rounded-pill px-3">{{ $customer->transactions->count() }} total</span>
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
                                <th class="border-0 py-3 small text-muted text-uppercase">Initiated By</th>
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
                                <td>
                                    <span class="badge {{ $txn->initiated_by === 'customer' ? 'bg-info-subtle text-info' : 'bg-secondary-subtle text-secondary' }} rounded-pill px-2">
                                        {{ strtoupper($txn->initiated_by) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $txn->status_badge }} rounded-pill px-3">{{ strtoupper($txn->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">No transaction history found for this customer.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
