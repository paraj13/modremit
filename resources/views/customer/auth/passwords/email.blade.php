@extends('layouts.auth-simple')

@section('title', 'Forgot Password — Modremit')

@section('content')
<div class="auth-card">
    <div class="mb-4 d-flex justify-content-center">
        <x-logo />
    </div>
    
    <h4 class="fw-bold mb-2">Forgot Password?</h4>
    <p class="text-muted small mb-4">Enter your email and we'll send you a link to reset your password.</p>

    @if (session('status'))
        <div class="alert alert-success rounded-4 border-0 mb-4 small" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('customer.password.email') }}" method="POST" id="forgotPasswordForm">
        @csrf
        <div class="mb-4">
            <label class="form-label small fw-bold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control form-control-premium @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="you@example.com" autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-brand w-100 py-3 fs-6">Send Reset Link</button>
        
        <div class="text-center mt-4 pt-2">
            <p class="small text-muted mb-0"><a href="{{ route('customer.login') }}" class="text-brand-dark fw-bold text-decoration-none">← Back to Sign In</a></p>
        </div>
    </form>
</div>
@endsection
