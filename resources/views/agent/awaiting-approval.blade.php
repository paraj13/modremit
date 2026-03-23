@extends('layouts.agent')
@section('title', 'Awaiting Admin Approval')
@section('page_title', 'Account Under Review')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <div class="bg-brand-mint rounded-circle d-inline-flex align-items-center justify-content-center mb-3 icon-circle-2xl">
                        <i class="bi bi-clock-history text-brand-dark icon-xxl"></i>
                    </div>
                    <h3 class="fw-800 text-brand-dark mb-2">Verification Complete!</h3>
                    <p class="text-muted">Your identity has been verified. Your account is now being reviewed by our administrators. This usually takes less than 24 hours.</p>
                </div>

                <div class="alert alert-info rounded-4 p-4 text-start">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                        <div>
                            <p class="mb-0 small fw-semibold">We will notify you via email once your account is fully activated. Thank you for your patience!</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-center mt-4">
                    <a href="{{ route('agent.awaiting-approval') }}" class="btn btn-outline-dark px-4 py-2 fw-bold">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Status
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light text-muted px-4 py-2 fw-bold">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
