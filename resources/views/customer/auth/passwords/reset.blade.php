@extends('layouts.auth-simple')

@section('title', 'Reset Password — Modremit')

@section('content')
<div class="auth-card">
    <div class="mb-4 d-flex justify-content-center">
        <x-logo />
    </div>
    
    <h4 class="fw-bold mb-2">Reset Password</h4>
    <p class="text-muted small mb-4">Please choose a new, strong password for your account.</p>

    <form action="{{ route('customer.password.update') }}" method="POST" id="resetPasswordForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="form-label small fw-bold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control form-control-premium @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required readonly>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">New Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control form-control-premium @error('password') is-invalid @enderror" required placeholder="••••••••" autofocus>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirmation" class="form-control form-control-premium" required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn btn-brand w-100 py-3 fs-6">Update Password</button>
    </form>
</div>
@endsection
