<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modremit')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/brand.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: var(--brand-dark);
            --content-bg: #F0F4F8;
        }
        body {
            background-color: var(--content-bg);
            font-family: 'Inter', sans-serif;
        }
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: var(--sidebar-bg);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.6);
            padding: 14px 24px;
            margin: 4px 16px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        #sidebar .nav-link:hover {
            color: var(--brand-lime);
            background: rgba(255, 255, 255, 0.03);
        }
        #sidebar .nav-link.active {
            color: var(--brand-dark);
            background: var(--brand-lime);
            font-weight: 700;
        }
        #sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
        }
        #content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
        }
        .page-header {
            margin-bottom: 40px;
        }
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
</head>
<body>
    <div id="sidebar">
        <div class="px-4 py-5 mb-2">
            <h3 class="mb-0 fw-800 text-brand-lime" style="letter-spacing: -1.5px;">MODREMIT</h3>
            <span class="badge-premium mt-2 d-inline-block">@yield('role_badge', 'Portal')</span>
        </div>
        <nav class="nav flex-column mt-2">
            @yield('sidebar_nav')
            <div class="mt-auto px-4 pb-4">
                <hr class="my-4" style="opacity: 0.05;">
                <form action="{{ route('logout') }}" method="POST">
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
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert" style="border-radius: 16px;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center page-header">
            <div>
                <h2 class="mb-1 fw-800 text-brand-dark" style="letter-spacing: -1px;">@yield('page_title')</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item small text-muted">Workspace</li>
                        <li class="breadcrumb-item small active fw-bold text-brand-dark">@yield('page_title')</li>
                    </ol>
                </nav>
            </div>
            <div class="user-info d-flex align-items-center bg-white p-2 rounded-4 shadow-sm">
                <div class="bg-brand-mint p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-person-fill text-brand-dark fs-5"></i>
                </div>
                <div class="text-start me-3">
                    <div class="fw-bold text-brand-dark small leading-tight">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
