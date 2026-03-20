<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Modremit</title>
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
        <h4 class="fw-bold mb-2">Forgot Password?</h4>
        <p class="text-muted small mb-4">No worries! Enter your email and we'll send you a link to reset your password.</p>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: "{!! session('success') !!}",
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-4 shadow-sm border-0' }
                    });
                });
            </script>
        @endif

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

        <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm">
            @csrf
            <div class="mb-4">
                <label class="form-label small fw-bold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control form-control-premium" placeholder="name@company.com" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn btn-brand w-100 py-3 fs-6">Send Reset Link</button>
            
            <div class="text-center mt-4 pt-2">
                <p class="small text-muted mb-0">Remembered your password? <a href="{{ route('login') }}" class="text-brand-dark fw-bold text-decoration-none">Back to Login</a></p>
            </div>
        </form>
    </div>
    
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/app-global.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.initGlobalValidation('forgotPasswordForm', {
                email: { required: true, email: true }
            });
        });
    </script>
</body>
</html>
