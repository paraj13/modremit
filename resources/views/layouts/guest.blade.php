<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'Modremit')</title>
    
    <!-- Fonts & Icons -->
    <link href="{{ asset('vendor/css/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/flag-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/brand.css') }}" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: #142472;
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .btn-brand {
            background-color: #FFDE42;
            color: #142472;
            font-weight: 700;
            border-radius: 50px;
            padding: 10px 25px;
            border: none;
            transition: all 0.2s;
        }
        .btn-brand:hover {
            background-color: #FFDE42;
            transform: translateY(-1px);
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg py-3 bg-white shadow-sm sticky-top">
        <div class="container">
            <x-logo />
            <div class="ms-auto d-flex align-items-center">
                <a href="{{ route('customer.login') }}" class="nav-link me-3 fw-bold">{{ __('messages.login') }}</a>
                <a href="{{ route('customer.register') }}" class="btn-brand text-decoration-none">{{ __('messages.register') }}</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-brand-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">

                    <a class="navbar-brand d-flex align-items-center {{ $class ?? '' }}" href="{{ url('/') }}">
                        
                        <div class="bg-brand-light text-brand-dark rounded-3 d-flex align-items-center justify-content-center me-3 icon-circle-lg">
                            <i class="bi bi-send-fill brand-icon"></i>
                        </div>

                        <span class="fw-bold text-light brand-logo-text">
                            MOD<span class="text-brand-dark bg-brand-light px-2 ms-1 rounded">REMIT</span>
                        </span>

                    </a>
                    <p class="opacity-50 small mt-2 mb-4 pe-lg-5">
                        {{ __('messages.footer_desc') }}
                    </p>
                    <div class="d-flex gap-3 fs-4 text-brand-lime">
                        <i class="bi bi-facebook"></i><i class="bi bi-twitter-x"></i><i class="bi bi-linkedin"></i>
                    </div>
                </div>
                <div class="col-lg-3">
                    <h6 class="fw-bold mb-4">{{ __('messages.company') }}</h6>
                    <ul class="list-unstyled opacity-50 small">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.about_us') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.careers') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.privacy_policy') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="fw-bold mb-4">{{ __('messages.support') }}</h6>
                    <ul class="list-unstyled opacity-50 small">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.help_center') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.contact_us') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.security') }}</a></li>
                    </ul>
                </div>

            </div>
            <hr class="my-5 opacity-10">
            <div class="text-center opacity-50 small">
                © {{ date('Y') }} Modremit. All rights reserved.
            </div>
        </div>
    </footer>
    

    <!-- Scripts -->
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
