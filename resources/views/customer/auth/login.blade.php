@extends('layouts.auth-simple')

@section('title', 'Customer Login — Modremit')

@section('content')
<div class="auth-card">
    <div class="mb-4 d-flex justify-content-center">
        <x-logo />
    </div>
    
    <h4 class="fw-bold mb-2">Welcome Back</h4>
    <p class="text-muted small mb-4">Please sign in to your customer account to continue.</p>

    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 mb-4 small">{{ session('success') }}</div>
    @endif

    <form action="{{ route('customer.login') }}" method="POST" id="customerLoginForm">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-bold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control form-control-premium @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <label class="form-label small fw-bold">Password</label>
                <a href="{{ route('customer.password.request') }}" class="small text-decoration-none text-muted">Forgot?</a>
            </div>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control form-control-premium @error('password') is-invalid @enderror" placeholder="••••••••" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-brand w-100 py-3 fs-6">Sign In</button>
        
        <div class="text-center mt-4 pt-2">
            <p class="small text-muted mb-0">New customer? <a href="{{ route('customer.register') }}" class="text-brand-dark fw-bold text-decoration-none">Register here</a></p>
            <p class="mt-2 small"><a href="{{ route('home') }}" class="text-muted text-decoration-none">← Back to home</a></p>
        </div>
    </form>
</div>
@endsection
