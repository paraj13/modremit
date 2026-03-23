@extends('layouts.auth-simple')

@section('title', 'Customer Registration — Modremit')

@section('content')
<div class="auth-card auth-card-sm">
    <div class="mb-4 d-flex justify-content-center">
        <x-logo />
    </div>
    
    <h4 class="fw-bold mb-2">Create Account</h4>
    <p class="text-muted small mb-4">Join Modremit and start sending money worldwide.</p>

    <form action="{{ route('customer.register') }}" method="POST" id="customerRegisterForm">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-bold">Full Name</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                <input type="text" name="name" class="form-control form-control-premium @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control form-control-premium @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="you@example.com" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Phone Number</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-phone"></i></span>
                <input type="text" name="phone" class="form-control form-control-premium @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+1234567890" required>
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control form-control-premium @error('password') is-invalid @enderror" placeholder="••••••••" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" class="form-control form-control-premium" placeholder="••••••••" required>
                </div>
            </div>
            @error('password') <div class="col-12 mt-1 small text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-brand w-100 py-3 fs-6 mt-2">Register Now</button>
        
        <div class="text-center mt-4 pt-2">
            <p class="small text-muted mb-0">Already have an account? <a href="{{ route('customer.login') }}" class="text-brand-dark fw-bold text-decoration-none">Sign in here</a></p>
        </div>
    </form>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.initGlobalValidation('customerRegisterForm', {
            name: { required: true },
            email: { required: true, email: true },
            phone: { required: true },
            password: { required: true, minlength: 8 },
            password_confirmation: { required: true, equalTo: '#password' }
        });
    });
</script>
@endpush
@endsection
