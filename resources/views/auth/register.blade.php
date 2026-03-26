@extends('layouts.auth-simple')

@section('title', 'Agent Registration | Modremit')

@section('content')
<div class="auth-card register-card-custom">
    <div class="mb-4 d-flex justify-content-center">
        <x-logo />
    </div>

    <h4 class="fw-bold mb-2 text-center">Create Agent Account</h4>
    <p class="text-muted small text-center mb-4">
        Join our global network and start sending money worldwide.
    </p>

    <form action="{{ route('register') }}" method="POST" id="registerForm">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" class="form-control form-control-premium" placeholder="John Doe" value="{{ old('name') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control form-control-premium" placeholder="john@example.com" value="{{ old('email') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-phone"></i></span>
                    <input type="text" name="phone" class="form-control form-control-premium" placeholder="+41 71 123 45 67" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-globe"></i></span>
                    <select name="country" class="form-control form-control-premium @error('country') is-invalid @enderror">
                        <option value="">Select Country</option>
                        @foreach(\App\Constants\CountryCurrency::COUNTRIES as $c)
                            <option value="{{ $c['name'] }}" {{ old('country') == $c['name'] ? 'selected' : '' }}>
                                {{ $c['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control form-control-premium" placeholder="create password">
                    <span class="input-group-text bg-light border-0 cursor-pointer toggle-password" data-target="#password">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-premium" placeholder="confirm password">
                    <span class="input-group-text bg-light border-0 cursor-pointer toggle-password" data-target="#password_confirmation">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-brand w-100 py-3 mt-4">Submit Application</button>

        <div class="text-center mt-4">
            <p class="small text-muted mb-0">Already have an account? <a href="{{ route('login') }}" class="fw-bold text-decoration-none link-brand">Login here</a></p>
        </div>

    </form>
</div>
@push('styles')
<style>
.register-card-custom { max-width: 700px !important; }
.cursor-pointer { cursor: pointer; }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.initGlobalValidation('registerForm', {
            name: { required: true },
            email: { required: true, email: true },
            phone: { required: true },
            country: { required: true },
            password: { required: true, minlength: 8 },
            password_confirmation: { required: true, equalTo: '#password' }
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