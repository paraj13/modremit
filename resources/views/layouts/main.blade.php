<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modremit')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('vendor/css/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/flag-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/brand.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/js/dataTables.bootstrap5.min.js') }}"></script>
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
        <div class="px-4 py-3">
        <x-logo-light />
        <hr>
            <!-- <span class="badge-premium mt-2 d-inline-block">@yield('role_badge', 'Portal')</span> -->
        </div>
        <nav class="nav flex-column">
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
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: "{!! session('success') !!}",
                        showConfirmButton: false,
                        showCloseButton: true,
                        closeButtonHtml: '&times;',
                        timer: 5000,
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-4 shadow-sm border-0' }
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: "{!! session('error') !!}",
                        showConfirmButton: false,
                        showCloseButton: true,
                        closeButtonHtml: '&times;',
                        timer: 5000,
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-4 shadow-sm border-0' }
                    });
                });
            </script>
        @endif

        @if($errors->any())
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

    <script src="{{ asset('vendor/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/js/additional-methods.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global jQuery Validation Defaults
            if (jQuery.validator) {
                jQuery.validator.setDefaults({
                    errorElement: 'span',
                    errorClass: 'invalid-feedback',
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    },
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.hasClass('choices__input')) {
                           error.insertAfter(element.closest('.choices'));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                // Custom method for Phone
                jQuery.validator.addMethod("phoneFormat", function(value, element) {
                    return this.optional(element) || /^\+?[0-9\s\-()]{7,20}$/.test(value);
                }, "Please enter a valid phone number.");
            }

            // Manual button loader utility
            window.showLoader = function(btn) {
                if (!btn) return;
                btn.classList.add('btn-loading');
                if (btn.classList.contains('btn-light') || btn.classList.contains('btn-brand-mint')) {
                    btn.classList.add('btn-loading-dark');
                }
            };

            window.removeLoader = function(btn) {
                if (!btn) return;
                btn.classList.remove('btn-loading');
                btn.classList.remove('btn-loading-dark');
            };

            // Enhanced Global Form Handler
            document.querySelectorAll('form').forEach(form => {
                const onsubmitStr = form.getAttribute('onsubmit');
                
                // Case A: Form has "return confirm"
                if (onsubmitStr && onsubmitStr.includes('return confirm')) {
                    const msgMatch = onsubmitStr.match(/confirm\(['"](.*?)['"]\)/);
                    const msg = msgMatch ? msgMatch[1] : 'Are you sure?';
                    
                    form.removeAttribute('onsubmit');
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const btn = e.submitter || form.querySelector('button[type="submit"]');
                        
                        Swal.fire({
                            title: 'Are you sure?',
                            text: msg,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#142472',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, proceed',
                            customClass: { popup: 'rounded-4 border-0 shadow' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                showLoader(btn);
                                form.submit();
                            } else {
                                removeLoader(btn);
                            }
                        });
                    });
                } 
                // Case B: Regular Form (including jQuery validated ones)
                else {
                    form.addEventListener('submit', function(e) {
                        // Check jQuery validation if present
                        if (window.jQuery && $(form).data('validator') && !$(form).valid()) {
                            return;
                        }

                        const submitBtn = e.submitter || form.querySelector('button[type="submit"]');
                        if (submitBtn && !submitBtn.classList.contains('no-loader')) {
                            showLoader(submitBtn);
                        }
                    });
                }
            });
        });
    </script>
    <script src="{{ asset('js/live-search.js') }}"></script>
    @stack('scripts')
</body>
</html>
