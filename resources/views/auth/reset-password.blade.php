<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Modremit</title>
    <link href="{{ asset('vendor/css/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/brand.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: var(--brand-lime);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: var(--white);
            border-radius: 32px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="mb-4 d-flex justify-content-center">
            <x-logo />
        </div>
        <h4 class="fw-bold mb-2">Reset Password</h4>
        <p class="text-muted small mb-4">Secure your account with a new password.</p>

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Validation Error',
                        html: '{!! implode("<br>", $errors->all()) !!}',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-4 shadow-sm border-0' }
                    });
                });
            </script>
        @endif

        <form action="{{ route('password.update') }}" method="POST" id="resetPasswordForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label small fw-bold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control form-control-premium" value="{{ $email ?? old('email') }}" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control form-control-premium" placeholder="••••••••" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock-check"></i></span>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-premium" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-brand w-100 py-3 fs-6">Reset Password</button>
        </form>
    </div>
    
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($.validator) {
                $("#resetPasswordForm").validate({
                    rules: {
                        email: { required: true, email: true },
                        password: { required: true, minlength: 8 },
                        password_confirmation: { required: true, equalTo: "#password" }
                    },
                    messages: {
                        email: {
                            required: "Email is required",
                            email: "Please enter a valid email address"
                        },
                        password: {
                            required: "Please enter a new password",
                            minlength: "Password must be at least 8 characters"
                        },
                        password_confirmation: {
                            required: "Please confirm your new password",
                            equalTo: "Passwords do not match"
                        }
                    },
                    errorElement: 'span',
                    errorClass: 'invalid-feedback',
                    highlight: function(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.closest('.input-group'));
                    },
                    submitHandler: function(form) {
                        const btn = $(form).find('button[type="submit"]');
                        btn.addClass('btn-loading');
                        form.submit();
                    }
                });
            }
        });
    </script>
</body>
</html>
