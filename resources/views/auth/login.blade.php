<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Modremit</title>
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
        .brand-logo {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -1px;
            color: var(--brand-dark);
            text-align: center;
            margin-bottom: 30px;
            display: block;
            text-decoration: none;
        }
        .alert {
            border-radius: 12px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="mb-4 d-flex justify-content-center">
            <x-logo />
        </div>
        <h4 class="fw-bold mb-2">Welcome Back</h4>
        <p class="text-muted small mb-4">Please enter your credentials to access your dashboard.</p>

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

        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control form-control-premium" placeholder="name@company.com" value="{{ old('email') }}" autofocus>
                </div>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label small fw-bold">Password</label>
                    <a href="{{ route('password.request') }}" class="small text-decoration-none text-muted">Forgot?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control form-control-premium" placeholder="••••••••">
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
    
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/app-global.js') }}"></script>
</body>
</html>
