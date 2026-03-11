<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modremit')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #0d6efd;
            --sidebar-bg: #1e293b;
        }
        body {
            background-color: #f8f9fa;
        }
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: var(--sidebar-bg);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }
        #sidebar .nav-link {
            color: #94a3b8;
            padding: 12px 20px;
            margin-bottom: 4px;
            border-radius: 8px;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        #sidebar .nav-link i {
            margin-right: 10px;
        }
        #content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 12px;
        }
        .stat-card {
            padding: 20px;
        }
        .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <div class="px-4 py-4">
            <h4 class="mb-0 fw-bold">Modremit</h4>
            <span class="badge bg-primary mt-1">@yield('role_badge', 'Portal')</span>
        </div>
        <nav class="nav flex-column px-3 mt-4">
            @yield('sidebar_nav')
            <hr class="my-4" style="opacity: 0.1;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    <div id="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">@yield('page_title')</h2>
            <div class="user-info text-end">
                <span class="fw-bold">{{ auth()->user()->name }}</span><br>
                <small class="text-muted">{{ auth()->user()->email }}</small>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
