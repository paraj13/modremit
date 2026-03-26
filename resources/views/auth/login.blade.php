@extends('layouts.auth-simple')

@section('title', 'Agent Login — Modremit')

@section('content')
<div class="auth-card">
    <div class="mb-4 d-flex justify-content-center">
        <x-logo />
    </div>
    <h4 class="fw-bold mb-2">Welcome Back</h4>
    <p class="text-muted small mb-4">Please enter your credentials to access your dashboard.</p>

    @if(session('success'))
        <div class="alert alert-success rounded-4 border-0 mb-4 small">{{ session('success') }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST" id="loginForm">
        @csrf
        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control form-control-premium @error('email') is-invalid @enderror" placeholder="name@company.com" value="{{ old('email') }}" autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-4">

            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control form-control-premium @error('password') is-invalid @enderror" placeholder="••••••••">
                <span class="input-group-text bg-light border-0 cursor-pointer toggle-password" data-target="#password">
                    <i class="bi bi-eye"></i>
                </span>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="d-flex justify-content-end mt-2">
                <a href="{{ route('password.request') }}" class="small text-decoration-none text-muted">Forgot?</a>
            </div>
        </div>

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-muted" for="remember">Remember me</label>
            </div>
        </div>

        <button type="submit" class="btn btn-brand w-100 py-3 fs-6">Sign In</button>
        
        <div class="text-center mt-4 pt-2">
            <p class="small text-muted mb-0">Don't have an agent account? <a href="{{ route('register') }}" class="text-brand-dark fw-bold text-decoration-none">Apply now</a></p>
        </div>
    </form>
</div>
@push('styles')
<style>
.cursor-pointer { cursor: pointer; }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.initGlobalValidation('loginForm', {
            email: { required: true, email: true },
            password: { required: true }
        });

        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.querySelector(targetId);
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    });
</script>
@endpush
@endsection
