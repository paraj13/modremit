@extends('layouts.admin')

@section('page_title', 'Review Flagged Transaction')

@section('content')

@php
    $customer = $log->transaction->customer;
    $sumsub = app(\App\Integrations\Sumsub\SumsubKycService::class);
    $kycData = $sumsub->getApplicantData($customer->sumsub_applicant_id ?? 'dummy_applicant_missing');
@endphp

<div class="row g-4 mb-4">
    <!-- Top Left: Transaction Details -->
    <div class="col-md-8">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Transaction Details #{{ $log->transaction->id }}</h5>
                <span class="status-pill status-pending shadow-sm">
                    PENDING COMPLIANCE
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label class="text-muted small fw-bold text-uppercase letter-spacing-1">Creating Agent</label>
                        <div class="fs-5 fw-bold">{{ $log->transaction->agent->name ?? 'Direct / Platform' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small fw-bold text-uppercase letter-spacing-1">Recipient</label>
                        <div class="fs-5 fw-bold">{{ $log->transaction->recipient->name }}</div>
                    </div>
                </div>
                <div class="row bg-light rounded-3 p-4 mb-4 gx-4">
                    <div class="col-sm-4 border-end border-light-subtle">
                        <label class="text-muted small fw-bold text-uppercase">CHF Amount</label>
                        <div class="h4 fw-bold text-brand-dark mb-0">{{ number_format($log->transaction->chf_amount, 2) }} CHF</div>
                    </div>
                    <div class="col-sm-4 border-end border-light-subtle">
                        <label class="text-muted small fw-bold text-uppercase">Exchange Rate</label>
                        <div class="h5 fw-bold text-muted mb-0">{{ number_format($log->transaction->rate, 4) }}</div>
                    </div>
                    <div class="col-sm-4">
                        <label class="text-muted small fw-bold text-uppercase">Target Amount</label>
                        <div class="h4 fw-bold text-brand-mint mb-0">{{ number_format($log->transaction->target_amount, 2) }} {{ $log->transaction->target_currency }}</div>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="text-muted small fw-bold text-uppercase">Reason for Flag</label>
                    <div class="alert alert-danger mb-0 border-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        {{ $log->reason }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Right: Customer & KYC Meta -->
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-brand-dark text-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Customer Details</h6>
                <span class="status-pill status-{{ $customer->kyc_status }} small shadow-sm">
                    {{ strtoupper($customer->kyc_status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="mb-3 border-bottom pb-2">
                    <div class="text-muted small fw-bold">Name</div>
                    <div class="fw-bold">{{ $customer->name }}</div>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <div class="text-muted small fw-bold">Email / Phone</div>
                    <div>{{ $customer->email }}<br>{{ $customer->phone }}</div>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <div class="text-muted small fw-bold">Country</div>
                    <div>{{ $customer->nationality ?? 'N/A' }}</div>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <div class="text-muted small fw-bold">Document Type</div>
                    <div class="fw-bold text-primary">{{ str_replace('_', ' ', $kycData['document_type']) }}</div>
                </div>
                <div class="mb-0">
                    <div class="text-muted small fw-bold">Sumsub Applicant ID</div>
                    <div class="small font-monospace">{{ $customer->sumsub_applicant_id ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Middle Row: Full Width Images Array -->
<div class="row g-4 mb-4">
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
                            <img src="{{ $kycData['doc_front'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" alt="Doc Front">
                        @else
                            <div class="bg-white border rounded py-5 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-90">
                                <i class="bi bi-file-earmark-image fs-1 text-light-emphasis mb-2"></i>
                                Not available
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-muted small fw-bold mb-3 text-uppercase">ID Document (Back)</div>
                        @if($kycData['doc_back'])
                            <img src="{{ $kycData['doc_back'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" alt="Doc Back">
                        @else
                            <div class="bg-white border rounded py-5 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-90">
                                <i class="bi bi-file-earmark-image fs-1 text-light-emphasis mb-2"></i>
                                Not available
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="text-muted small fw-bold mb-3 text-uppercase">Selfie Match</div>
                        @if($kycData['selfie'])
                            <img src="{{ $kycData['selfie'] }}" class="img-fluid rounded border shadow-sm bg-white p-1" alt="Selfie">
                        @else
                            <div class="bg-white border rounded py-5 text-muted small shadow-sm d-flex flex-column align-items-center justify-content-center h-90">
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

<!-- Bottom Row: Compliance Action Form -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm border-top border-4 border-primary">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Compliance Review Notes</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.compliance.review', $log->id) }}" method="POST" id="complianceReviewForm">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Review Notes</label>
                        <textarea name="notes" class="form-control bg-light" rows="3" placeholder="Document all findings and reasoning here for record keeping..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-3 align-items-center">
                        <button type="submit" name="action" value="review" class="btn btn-primary px-5 py-2 fw-bold text-white shadow">
                            <i class="bi bi-check-circle me-2"></i> Record Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    window.initGlobalValidation('complianceReviewForm', {
        notes: {
            required: true,
            minlength: 10
        }
    }, {
        notes: {
            required: "Please enter review notes before submitting",
            minlength: "Review notes must be at least 10 characters long"
        }
    });
});
</script>
@endpush
@endsection
