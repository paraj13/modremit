@extends('layouts.customer')
@section('title', 'KYC Verification Required')
@section('page_title', 'KYC Verification Required')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    @if($customer->kyc_status === 'rejected')
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:90px;height:90px;">
                            <i class="bi bi-shield-x text-danger" style="font-size:2.5rem;"></i>
                        </div>
                        <h3 class="fw-800 text-danger mb-2">KYC Rejected</h3>
                        <p class="text-muted">Your identity verification was rejected. Please try again or contact support.</p>
                    @elseif($customer->kyc_status === 'submitted')
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:90px;height:90px;">
                            <i class="bi bi-hourglass-split text-warning" style="font-size:2.5rem;"></i>
                        </div>
                        <h3 class="fw-800 text-brand-dark mb-2">KYC Under Review</h3>
                        <p class="text-muted">Your documents are being reviewed. This typically takes 1–2 business days. We'll notify you once done.</p>
                    @else
                        <div class="bg-brand-mint rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:90px;height:90px;">
                            <i class="bi bi-shield-lock text-brand-dark" style="font-size:2.5rem;"></i>
                        </div>
                        <h3 class="fw-800 text-brand-dark mb-2">Complete Your Verification</h3>
                        <p class="text-muted">To use Modremit, you must complete identity verification (KYC). This only takes a few minutes.</p>
                    @endif
                </div>

                <div class="bg-light rounded-4 p-4 mb-4 text-start">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                        <span class="small fw-semibold">Upload a government-issued ID (Passport or ID Card)</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                        <span class="small fw-semibold">Take a quick selfie for identity matching</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                        <span class="small fw-semibold">Your data is encrypted and secure</span>
                    </div>
                </div>

                @if($verificationLink)
                    <a href="{{ $verificationLink }}" target="_blank" class="btn btn-brand btn-lg w-100 py-3 fw-bold mb-3">
                        <i class="bi bi-shield-check me-2"></i> Start / Continue KYC Verification
                    </a>
                @else
                    <div class="alert alert-warning rounded-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Unable to generate verification link. Please contact support or try logging out and back in.
                    </div>
                @endif

                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light text-muted small py-2 px-4">Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
