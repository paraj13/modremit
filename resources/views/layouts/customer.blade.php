<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modremit — Customer Portal')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('vendor/css/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/flag-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/brand.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: var(--brand-dark);
            --content-bg: #F0F4F8;
        }
        body { background-color: var(--content-bg); font-family: 'Inter', sans-serif; }
        #sidebar {
            width: var(--sidebar-width); height: 100vh; position: fixed;
            background: var(--sidebar-bg); color: white; z-index: 1000;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.6); padding: 14px 24px; margin: 4px 16px;
            border-radius: 12px; font-weight: 500; display: flex; align-items: center; transition: all 0.2s;
        }
        #sidebar .nav-link:hover { color: var(--brand-lime); background: rgba(255,255,255,0.03); }
        #sidebar .nav-link.active { color: var(--brand-dark); background: var(--brand-lime); font-weight: 700; }
        #sidebar .nav-link i { margin-right: 12px; font-size: 1.2rem; }
        #content { margin-left: var(--sidebar-width); padding: 40px; min-height: 100vh; }
        .page-header { margin-bottom: 40px; }
        .badge-premium {
            background: var(--brand-mint);
            color: var(--brand-dark);
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="sidebar">
        <div class="px-4 py-3">
            <x-logo-light />
            <hr>
        </div>
        <nav class="nav flex-column mt-2">
            <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('customer.transfers.*') ? 'active' : '' }}" href="{{ route('customer.transfers.create') }}">
                <i class="bi bi-send-fill"></i> Send Money
            </a>
            <a class="nav-link {{ request()->routeIs('customer.transactions.*') ? 'active' : '' }}" href="{{ route('customer.transactions.index') }}">
                <i class="bi bi-journal-text"></i> Transactions
            </a>
            <a class="nav-link {{ request()->routeIs('customer.recipients.*') ? 'active' : '' }}" href="{{ route('customer.recipients.index') }}">
                <i class="bi bi-person-lines-fill"></i> Recipients
            </a>
            <div class="mt-auto px-4 pb-4" style="position: absolute; bottom: 0; width: 100%;">
                <hr style="opacity: 0.05;">
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent py-3 px-3 rounded-3 text-white-50" style="margin:0;">
                        <i class="bi bi-box-arrow-right me-2 text-danger"></i> Sign Out
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <div id="content">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{!! session('success') !!}", showConfirmButton: false, timer: 5000, timerProgressBar: true, customClass: { popup: 'rounded-4 shadow-sm border-0' } });
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{!! session('error') !!}", showConfirmButton: false, timer: 5000, timerProgressBar: true, customClass: { popup: 'rounded-4 shadow-sm border-0' } });
                });
            </script>
        @endif
        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Validation Error', html: '{!! implode("<br>", $errors->all()) !!}', showConfirmButton: false, timer: 6000, timerProgressBar: true, customClass: { popup: 'rounded-4 shadow-sm border-0' } });
                });
            </script>
        @endif

        <div class="d-flex justify-content-between align-items-center page-header">
            <div>
                <h2 class="mb-1 fw-800 text-brand-dark" style="letter-spacing: -1px;">@yield('page_title')</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item small text-muted">Customer Portal</li>
                        <li class="breadcrumb-item small active fw-bold text-brand-dark">@yield('page_title')</li>
                    </ol>
                </nav>
            </div>
            <div class="user-info d-flex align-items-center bg-white p-2 rounded-4 shadow-sm">
                <div class="bg-brand-lime p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-person-fill text-brand-dark fs-5"></i>
                </div>
                <div class="text-start me-3">
                    <div class="fw-bold text-brand-dark small leading-tight">{{ auth()->guard('customer')->user()->name }}</div>
                    <div class="text-muted d-flex align-items-center" style="font-size: 0.7rem;">
                        <span class="badge bg-{{ auth()->guard('customer')->user()->kyc_badge }} rounded-pill me-1" style="font-size:0.6rem;">{{ strtoupper(auth()->guard('customer')->user()->kyc_status) }}</span>
                        {{ auth()->guard('customer')->user()->email }}
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="{{ asset('vendor/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/app-global.js') }}"></script>
    @stack('scripts')
</body>
</html>
