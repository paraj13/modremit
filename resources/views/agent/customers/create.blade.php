@extends('layouts.agent')

@section('page_title', 'Add New Customer')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 card-premium">
            <div class="card-header bg-white py-4 border-0 text-center">
                <h4 class="fw-800 text-brand-dark mb-1" style="letter-spacing: -1px;">Customer Registration</h4>
                <p class="text-muted small mb-0 uppercase fw-bold" style="letter-spacing: 1px;">Register a new sender on the platform</p>
            </div>
            <div class="card-body p-5 pt-2">
                <form action="{{ route('agent.customers.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-brand-dark">FULL LEGAL NAME</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-person text-brand-dark"></i></span>
                            <input type="text" name="name" class="form-control form-control-premium @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
                        </div>
                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-brand-dark">EMAIL ADDRESS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-brand-dark"></i></span>
                                <input type="email" name="email" class="form-control form-control-premium @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="john@example.com" required>
                            </div>
                            @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold small text-brand-dark">PHONE NUMBER</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-phone text-brand-dark"></i></span>
                                <input type="text" name="phone" class="form-control form-control-premium @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+41 7x xxx xx xx" required>
                            </div>
                            @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="alert bg-brand-mint border-0 rounded-4 p-4 mb-4 shadow-sm">
                        <div class="d-flex">
                            <i class="bi bi-shield-lock-fill text-brand-dark h3 mb-0 me-3"></i>
                            <div>
                                 <strong class="text-brand-dark">KYC Verification Required.</strong><br>
                                 <p class="text-brand-dark opacity-75 small mb-0 mt-1">
                                    Our platform ensures safety for all. After registration, the customer will receive an SMS/Email link to complete identity verification via our secure Sumsub integration.
                                 </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3 pt-2">
                        <button type="submit" class="btn btn-brand py-3 fs-6">
                             <i class="bi bi-person-check-fill me-2"></i> Confirm & Register Customer
                        </button>
                        <a href="{{ route('agent.customers.index') }}" class="btn btn-light rounded-3 py-2 text-muted small fw-bold text-uppercase">Cancel and go back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
