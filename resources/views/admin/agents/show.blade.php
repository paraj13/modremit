@extends('layouts.admin')

@section('title', 'Agent Details: ' . $agent->name)

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-brand-dark text-white p-4 h-100">
            <h6 class="text-brand-lime small fw-bold uppercase mb-2">Wallet Balance</h6>
            <h3 class="fw-bold mb-0">CHF {{ number_format($agent->wallet->chf_balance ?? 0, 2) }}</h3>
            <div class="mt-auto pt-3">
                <i class="bi bi-wallet2 text-brand-lime fs-4"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="text-muted small fw-bold uppercase mb-2">Total Added</h6>
            <h3 class="fw-bold mb-0 text-success">CHF {{ number_format($stats['total_added'], 2) }}</h3>
            <div class="mt-auto pt-3 text-success">
                <i class="bi bi-plus-circle fs-4"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="text-muted small fw-bold uppercase mb-2">Total Sent</h6>
            <h3 class="fw-bold mb-0 text-danger">CHF {{ number_format($stats['total_sent'], 2) }}</h3>
            <div class="mt-auto pt-3 text-danger">
                <i class="bi bi-send fs-4"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="text-muted small fw-bold uppercase mb-2">Agent Status</h6>
            <h3 class="fw-bold mb-0">
                <span class="badge {{ $agent->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                    {{ $agent->is_active ? 'ACTIVE' : 'INACTIVE' }}
                </span>
            </h3>
            <div class="mt-auto pt-3">
                <i class="bi bi-person-check fs-4 text-muted"></i>
            </div>
        </div>
    </div>
</div>

@php
    $sumsub = app(\App\Integrations\Sumsub\SumsubKycService::class);
    $kycData = $agent->sumsub_applicant_id ? $sumsub->getApplicantData($agent->sumsub_applicant_id) : null;
@endphp

@if($kycData)
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-brand-dark"><i class="bi bi-person-vcard text-muted me-2"></i> Agent KYC Documents</h5>
                @php
                    $badgeClass = match($agent->kyc_status ?? 'pending') {
                        'approved' => 'bg-brand-mint text-brand-dark',
                        'rejected' => 'bg-danger text-white',
                        default    => 'bg-warning text-dark'
                    };
                @endphp
                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill shadow-sm">
                    {{ strtoupper($agent->kyc_status ?? 'PENDING') }}
                </span>
            </div>
            <div class="card-body px-4 bg-light rounded-bottom-4">
                <div class="row g-4">
                    <div class="col-md-4 text-center border-end">
                        <div class="text-muted small fw-bold mb-3 text-uppercase">ID Document (Front)</div>
                        @if($kycData['doc_front'])
                            <img src="{{ $kycData['doc_front'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" style="max-height: 180px; object-fit: contain;" alt="Doc Front">
                        @else
                            <div class="bg-white border rounded py-4 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-100" style="min-height: 150px;">
                                <i class="bi bi-file-earmark-image fs-1 text-light-emphasis mb-2"></i>
                                Not available
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 text-center border-end">
                        <div class="text-muted small fw-bold mb-3 text-uppercase">ID Document (Back)</div>
                        @if($kycData['doc_back'])
                            <img src="{{ $kycData['doc_back'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" style="max-height: 180px; object-fit: contain;" alt="Doc Back">
                        @else
                            <div class="bg-white border rounded py-4 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-100" style="min-height: 150px;">
                                <i class="bi bi-file-earmark-image fs-1 text-light-emphasis mb-2"></i>
                                Not available
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-muted small fw-bold mb-3 text-uppercase">Selfie Match</div>
                        @if($kycData['selfie'])
                            <img src="{{ $kycData['selfie'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" style="max-height: 180px; object-fit: contain;" alt="Selfie">
                        @else
                            <div class="bg-white border rounded py-4 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-100" style="min-height: 150px;">
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
@endif

<div class="row g-4">
    <!-- Wallet history -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-4 px-4">
                <h5 class="fw-bold mb-0 text-brand-dark">Wallet Transaction History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3 small text-muted uppercase">Date</th>
                                <th class="border-0 py-3 small text-muted uppercase">Type</th>
                                <th class="border-0 py-3 small text-muted uppercase">Description</th>
                                <th class="border-0 py-3 small text-muted uppercase text-end">Amount</th>
                                <th class="border-0 px-4 py-3 small text-muted uppercase text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($agent->wallet && $agent->wallet->transactions)
                                @forelse($agent->wallet->transactions as $txn)
                                <tr>
                                    <td class="px-4 py-3 small">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 small fw-bold {{ $txn->type === 'deposit' ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info' }}">
                                            {{ ucfirst($txn->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $txn->description }}</div>
                                        @if($txn->payment_method)
                                            <div class="x-small text-muted text-uppercase">Via {{ $txn->payment_method }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold {{ $txn->amount > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $txn->amount > 0 ? '+' : '' }}{{ number_format($txn->amount, 2) }} CHF
                                    </td>
                                    <td class="px-4 text-center">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted">No transactions found.</td></tr>
                                @endforelse
                            @else
                                <tr><td colspan="5" class="text-center py-5 text-muted">No wallet found for this agent.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent Profile Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-4 px-4">
                <h5 class="fw-bold mb-0 text-brand-dark">Agent Profile</h5>
            </div>
            <div class="card-body px-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar bg-brand-lime text-brand-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        {{ substr($agent->name, 0, 1) }}
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-brand-dark">{{ $agent->name }}</h5>
                        <p class="text-muted mb-0 small">Member since {{ $agent->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <label class="x-small text-muted text-uppercase fw-bold d-block mb-1">Email Address</label>
                        <div class="fw-bold text-brand-dark">{{ $agent->email }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <label class="x-small text-muted text-uppercase fw-bold d-block mb-1">Phone Number</label>
                        <div class="fw-bold text-brand-dark">{{ $agent->phone ?? 'Not provided' }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <label class="x-small text-muted text-uppercase fw-bold d-block mb-1">Total Commission Earned</label>
                        <div class="fw-bold text-brand-dark">CHF {{ number_format($agent->wallet->total_commission ?? 0, 2) }}</div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-brand btn-lg rounded-3 py-3 fw-bold">
                        <i class="bi bi-pencil-square me-2"></i> Edit Account
                    </a>
                    <a href="{{ route('admin.wallets.credit.form', $agent->id) }}" class="btn btn-outline-dark btn-lg rounded-3 py-3 fw-bold">
                        <i class="bi bi-wallet2 me-2"></i> Manual Credit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
