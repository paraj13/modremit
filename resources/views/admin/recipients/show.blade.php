@extends('layouts.admin')

@section('page_title', 'Recipient Details')

@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <div class="card card-premium p-0 overflow-hidden h-100">
            <div class="bg-brand-dark py-5 text-center">
                <div class="bg-brand-mint text-brand-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-lg" style="width: 80px; height: 80px;">
                    <i class="bi bi-person-check h2 mb-0"></i>
                </div>
                <h4 class="fw-bold mb-1 text-white">{{ $recipient->name }}</h4>
                <p class="text-brand-lime small mb-0 opacity-75">Beneficiary Profile</p>
            </div>
            <div class="card-body p-4">
                <h6 class="fw-bold text-brand-dark mb-3">Bank Information</h6>
                <div class="space-y-3 mb-4">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="small text-muted fw-bold">BANK NAME</span>
                        <span class="small text-brand-dark fw-bold">{{ $recipient->bank_name }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="small text-muted fw-bold">ACCOUNT NUMBER</span>
                        <span class="small text-brand-dark fw-bold font-monospace">{{ $recipient->account_number }}</span>
                    </div>
                    @if($recipient->ifsc_code)
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="small text-muted fw-bold">IFSC CODE</span>
                        <span class="small text-brand-dark fw-bold">{{ $recipient->ifsc_code }}</span>
                    </div>
                    @endif
                    @if($recipient->upi_id)
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="small text-muted fw-bold">UPI ID</span>
                        <span class="small text-brand-lime fw-bold">{{ $recipient->upi_id }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between border-bottom pb-2">
                        <span class="small text-muted fw-bold">COUNTRY</span>
                        <span class="badge bg-light text-dark border px-3 rounded-pill">{{ $recipient->country }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card card-premium p-4 h-100">
            <h6 class="fw-bold text-brand-dark mb-4">Relationships</h6>
            <div class="bg-light p-4 rounded-4 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary me-3">
                        <i class="bi bi-person-up fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Associated Sender</h6>
                        <p class="text-muted small mb-0">The customer who sends money to this recipient</p>
                    </div>
                </div>
                <h4 class="fw-bold text-primary mb-3">{{ $recipient->customer->name }}</h4>
                <a href="{{ route('admin.customers.show', $recipient->customer_id) }}" class="btn btn-brand-outline btn-sm px-4 rounded-pill">View Sender Profile</a>
            </div>

            <div class="bg-light p-4 rounded-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success me-3">
                        <i class="bi bi-person-workspace fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Managed By Agent</h6>
                        <p class="text-muted small mb-0">The agent responsible for this connection</p>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-0">{{ $recipient->customer->agent->name ?? 'Direct / Platform' }}</h5>
                <p class="text-muted small">{{ $recipient->customer->agent->email ?? 'N/A' }}</p>
            </div>
            
            <div class="mt-auto pt-4 text-center">
                <a href="{{ route('admin.recipients.index') }}" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Back to All Beneficiaries
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
