<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication — Modremit')</title>
    <link href="{{ asset('vendor/css/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/brand.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <style>
        body { background-color: var(--brand-lime); min-height: 100vh; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: white; border-radius: 32px; padding: 40px; width: 100%; max-width: 450px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
    </style>
    @stack('styles')
</head>
<body class="d-flex align-items-center justify-content-center py-5">
    @yield('content')
    
    <script src="{{ asset('vendor/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/app-global.js') }}"></script>
    @stack('scripts')
</body>
</html>
