<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modremit - Global Money Transfers | Fast, Secure & Transparent</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/brand.css') }}" rel="stylesheet">
    
    <!-- Choices.js for Searchable Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        body {
            background-color: var(--white-home);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Hero Styling */
        .hero {
            background-color: var(--brand-lime);
            padding: 0 0 100px 0;
            position: relative;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -1px;
            color: var(--brand-dark) !important;
        }

        .nav-link {
            font-weight: 600;
            color: var(--brand-dark) !important;
            margin: 0 15px;
        }

        .hero-title {
            font-weight: 800;
            font-size: clamp(2.5rem, 6vw, 4.8rem);
            line-height: 0.9;
            letter-spacing: -3px;
            margin-bottom: 25px;
        }

        /* Converter Pod */
        .converter-pod {
            background: var(--white-home);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            max-width: 520px;
            margin: 40px auto 0 auto;
        }

        .accordion-button {
            background-color: var(--white-home);
        }

        .pod-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
            display: block;
        }

        .input-group-modern {
            background: var(--gray-light);
            border-radius: 16px;
            padding: 12px 16px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .amount-input {
            border: none;
            background: transparent;
            font-size: 1.5rem;
            font-weight: 700;
            width: 100%;
            outline: none;
        }

        /* Choices.js Customization */
        .choices__inner {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            min-height: auto !important;
        }
        .choices__list--single {
            padding: 0 !important;
        }
        .choices__item {
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Reviews Marquee */
        .marquee {
            overflow: hidden;
            white-space: nowrap;
            background: var(--brand-dark);
            padding: 60px 0;
            position: relative;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 40s linear infinite;
        }

        .review-card {
            display: inline-block;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 25px;
            margin: 0 15px;
            width: 300px;
            white-space: normal;
            vertical-align: top;
            color: var(--white-home);
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .star-rating {
            color: var(--brand-lime);
            margin-bottom: 10px;
        }

        /* Section Spacing */
        section {
            padding: 100px 0;
        }

        .section-header {
            margin-bottom: 60px;
        }

        .section-tag {
            background: var(--brand-mint);
            color: var(--brand-dark);
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: 800;
            font-size: 2.5rem;
            letter-spacing: -1px;
        }

        .country-flag {
            font-size: 1.5rem;
            line-height: 1;
        }

        .lang-switcher .nav-link {
            font-size: 0.9rem;
            padding: 4px 10px;
            border-radius: 8px;
        }
        .glass-nav {
            top: 0 !important;
            z-index: 1050;
        }

        .currency-dropdown{
            position:relative;
            width:180px;
        }

        .currency-trigger{
            width:100%;
            display:flex;
            align-items:center;
            gap:8px;
            padding:10px 12px;
            border-radius:12px;
            border:1px solid #e5e7eb;
            background:white;
            font-weight:600;
            cursor:pointer;
        }

        .flag-icon{
            width:18px;
            height:14px;
            object-fit:cover;
            border-radius:2px;
        }

        .currency-name{
            font-size:13px;
            color:var(--text-muted);
        }

        .dropdown-arrow{
            margin-left:auto;
            font-size:10px;
        }

        .currency-menu{
            position:absolute;
            top:calc(100% + 8px);
            left:0;
            width:220px;
            max-height: 300px;
            overflow-y: auto;
            background:white;
            border-radius:16px;
            border:1px solid rgba(0,0,0,0.05);
            box-shadow:0 15px 40px rgba(0,0,0,0.12);
            display:none;
            z-index:100;
            padding: 8px;
        }

        .currency-item{
            display:flex;
            align-items:center;
            gap:10px;
            padding:10px 12px;
            cursor:pointer;
        }

        .currency-item:hover{
            background:var(--gray-light);
        }

        .currency-item.active{
            background:var(--brand-mint);
        }



        /* Responsive Fixes */
        @media (max-width: 991px) {
            .hero-title { font-size: 3.5rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top glass-nav">
        <div class="container">
        <x-logo />

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#how-it-works">{{ __('messages.how_it_works') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#features">{{ __('messages.features') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#countries">{{ __('messages.countries') }}</a></li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <div class="lang-switcher d-flex me-4">
                            <a href="{{ route('lang.switch', 'en') }}" class="nav-link px-2 {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                            <span class="text-muted">|</span>
                            <a href="{{ route('lang.switch', 'de') }}" class="nav-link px-2 {{ app()->getLocale() == 'de' ? 'active' : '' }}">DE</a>
                        </div>
                        <a href="{{ route('login') }}" class="nav-link me-3">{{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}" class="btn-brand text-decoration-none">{{ __('messages.register') }}</a>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="hero">
        <div class="container pt-5">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="mb-3 d-inline-block">
                        <span class="badge bg-brand-dark text-brand-lime px-3 py-2 rounded-pill fw-bold fs-6 shadow-sm border border-dark">
                            <i class="bi bi-lightning-charge-fill me-1 text-warning"></i> Current Rate: 1 CHF = {{ number_format($baseRate ?? 0, 2) }} INR
                        </span>
                    </div>
                    <h1 class="hero-title">{{ __('messages.hero_title') }}</h1>
                    <p class="fs-5 text-dark-emphasis mb-5 pe-lg-5">
                        {{ __('messages.hero_subtitle') }}
                    </p>
                    <div class="d-flex gap-3 mb-5">
                        <a href="#converter" class="btn-brand px-5 py-3 fs-5 text-decoration-none">{{ __('messages.send_money') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-brand-outline px-5 py-3 fs-5 text-decoration-none">{{ __('messages.get_started') }}</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <!-- Converter Pod -->
                    <div class="converter-pod" id="converter">
                        <div class="mb-4">
                            <label class="pod-label">{{ __('messages.you_send') }}</label>
                            <div class="input-group-modern">
                                <input type="number" id="send_amount" class="amount-input" value="1000">
                                <div class="currency-dropdown" id="from_currency_dropdown">
                                    <input type="hidden" name="from_currency" id="from_currency" value="CHF">
                                    <button class="currency-trigger" type="button">
                                        <img src="https://flagcdn.com/ch.svg" class="flag-icon">
                                        <span class="currency-code">CHF</span>
                                        <span class="dropdown-arrow">▼</span>
                                    </button>

                                    <div class="currency-menu">
                                        <div class="currency-item" data-code="CHF" data-name="Swiss Franc">
                                            <img src="https://flagcdn.com/ch.svg" class="flag-icon">
                                            <div class="d-flex flex-column" style="line-height: 1.1;">
                                                <span class="fw-bold">CHF</span>
                                                <span class="currency-name" style="font-size: 10px;">Swiss Franc</span>
                                            </div>
                                        </div>

                                        <div class="currency-item" data-code="USD" data-name="US Dollar">
                                            <img src="https://flagcdn.com/us.svg" class="flag-icon">
                                            <div class="d-flex flex-column" style="line-height: 1.1;">
                                                <span class="fw-bold">USD</span>
                                                <span class="currency-name" style="font-size: 10px;">United States Dollar</span>
                                            </div>
                                        </div>

                                        <div class="currency-item" data-code="EUR" data-name="Euro">
                                            <img src="https://flagcdn.com/eu.svg" class="flag-icon">
                                            <div class="d-flex flex-column" style="line-height: 1.1;">
                                                <span class="fw-bold">EUR</span>
                                                <span class="currency-name" style="font-size: 10px;">Euro</span>
                                            </div>
                                        </div>

                                        <div class="currency-item" data-code="GBP" data-name="British Pound">
                                            <img src="https://flagcdn.com/gb.svg" class="flag-icon">
                                            <div class="d-flex flex-column" style="line-height: 1.1;">
                                                <span class="fw-bold">GBP</span>
                                                <span class="currency-name" style="font-size: 10px;">British Pound</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="position-relative my-4 ps-4 border-start border-2" style="border-color: #eee !important;">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold"><i class="bi bi-dash"></i> <span id="fee_amount">15.00</span> <span class="from_code">CHF</span></span>
                                <span class="text-muted small">{{ __('messages.fee') }}</span>
                            </div>
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold"><i class="bi bi-x"></i> <span id="fx_rate">0.8601</span></span>
                                <span class="text-muted small">{{ __('messages.rate') }}</span>
                            </div>
                            <div class="position-absolute top-50 start-0 translate-middle-x bg-white p-1" style="margin-left: -1px;">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                    <i class="bi bi-arrow-down-short text-muted"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="pod-label">{{ __('messages.recipient_gets') }}</label>
                            <div class="input-group-modern">
                                <input type="text" id="receive_amount" class="amount-input" readonly>
<div class="currency-dropdown" id="to_currency_dropdown">
    <input type="hidden" name="to_currency" id="to_currency" value="INR">

    <button class="currency-trigger" type="button">
        <img src="https://flagcdn.com/in.svg" class="flag-icon">
        <span class="currency-code">INR</span>
        <span class="dropdown-arrow">▼</span>
    </button>

    <div class="currency-menu">

        <div class="currency-item" data-code="INR" data-name="Indian Rupee">
            <img src="https://flagcdn.com/in.svg" class="flag-icon">
            <div class="d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold">INR</span>
                <span class="currency-name" style="font-size: 10px;">Indian Rupee</span>
            </div>
        </div>

        <div class="currency-item" data-code="EUR" data-name="Euro">
            <img src="https://flagcdn.com/eu.svg" class="flag-icon">
            <div class="d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold">EUR</span>
                <span class="currency-name" style="font-size: 10px;">Euro</span>
            </div>
        </div>

        <div class="currency-item" data-code="USD" data-name="United States Dollar">
            <img src="https://flagcdn.com/us.svg" class="flag-icon">
            <div class="d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold">USD</span>
                <span class="currency-name" style="font-size: 10px;">United States Dollar</span>
            </div>
        </div>

        <div class="currency-item" data-code="GBP" data-name="British Pound">
            <img src="https://flagcdn.com/gb.svg" class="flag-icon">
            <div class="d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold">GBP</span>
                <span class="currency-name" style="font-size: 10px;">British Pound</span>
            </div>
        </div>

        <div class="currency-item" data-code="CHF" data-name="Swiss Franc">
            <img src="https://flagcdn.com/ch.svg" class="flag-icon">
            <div class="d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold">CHF</span>
                <span class="currency-name" style="font-size: 10px;">Swiss Franc</span>
            </div>
        </div>

        <div class="currency-item" data-code="PHP" data-name="Philippine Peso">
            <img src="https://flagcdn.com/ph.svg" class="flag-icon">
            <div class="d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold">PHP</span>
                <span class="currency-name" style="font-size: 10px;">Philippine Peso</span>
            </div>
        </div>

        <div class="currency-item" data-code="PKR" data-name="Pakistani Rupee">
            <img src="https://flagcdn.com/pk.svg" class="flag-icon">
            <div class="d-flex flex-column" style="line-height: 1.1;">
                <span class="fw-bold">PKR</span>
                <span class="currency-name" style="font-size: 10px;">Pakistani Rupee</span>
            </div>
        </div>

    </div>
</div>
                            </div>
                        </div>

                        <div id="loader" class="text-center d-none mb-3">
                            <div class="spinner-border spinner-border-sm text-brand-lime" role="status"></div>
                        </div>

                        <a href="{{ route('register') }}" class="btn-brand w-100 py-3 text-center text-decoration-none d-block">
                            {{ __('messages.send_money') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Marquee -->
    <div class="marquee">
        <div class="container mb-4 text-center">
            <span class="text-white opacity-50 fw-bold text-uppercase small letter-spacing-1">{{ __('messages.trusted_by') }}</span>
        </div>
        <div class="marquee-content">
            @php
                $reviews = [
                    ['name' => 'Lukas', 'country' => 'ch', 'text' => 'Super schnell und fair. Endlich eine Alternative zu teuren Banken.', 'rating' => 5],
                    ['name' => 'Anita', 'country' => 'in', 'text' => 'Receiving money from Switzerland is now instant. Highly recommend!', 'rating' => 5],
                    ['name' => 'Marco', 'country' => 'eu', 'text' => 'Best rates I could find. Transparent and secure.', 'rating' => 5],
                    ['name' => 'Sarah', 'country' => 'gb', 'text' => 'Customer support is amazing. They helped me through my first transfer.', 'rating' => 5],
                    ['name' => 'Kevin', 'country' => 'us', 'text' => 'Simple, fast, and does exactly what it says.', 'rating' => 5],
                    ['name' => 'Elena', 'country' => 'ph', 'text' => 'Great platform for OFW. Very reliable.', 'rating' => 5],
                    ['name' => 'Ahmed', 'country' => 'pk', 'text' => 'Easy to use. Best way to send money home.', 'rating' => 5],
                    ['name' => 'Thomas', 'country' => 'de', 'text' => 'Sehr zufrieden mit dem Service. Top Kurse!', 'rating' => 5],
                    ['name' => 'Maria', 'country' => 'es', 'text' => 'Servicio excelente y muy rápido.', 'rating' => 5],
                    ['name' => 'David', 'country' => 'ca', 'text' => 'Fantastic experience. Low fees and great UX.', 'rating' => 5],
                    // Repeat for continuous scroll
                    ['name' => 'Lukas', 'country' => 'ch', 'text' => 'Super schnell und fair. Endlich eine Alternative zu teuren Banken.', 'rating' => 5],
                    ['name' => 'Anita', 'country' => 'in', 'text' => 'Receiving money from Switzerland is now instant. Highly recommend!', 'rating' => 5],
                    ['name' => 'Marco', 'country' => 'eu', 'text' => 'Best rates I could find. Transparent and secure.', 'rating' => 5],
                ];
            @endphp
            @foreach($reviews as $review)
            <div class="review-card">
                <div class="star-rating">
                    @for($i=0; $i<$review['rating']; $i++) <i class="bi bi-star-fill"></i> @endfor
                </div>
                <p class="mb-3 small">"{{ $review['text'] }}"</p>
                <div class="d-flex align-items-center">
                    <span class="fi fi-{{ $review['country'] }} me-2 rounded-circle border"></span>
                    <span class="small fw-bold">— {{ $review['name'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- How It Works -->
    <section id="how-it-works">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag">{{ __('messages.how_it_works') }}</span>
                <h2 class="section-title text-dark">{{ __('messages.transparent_simple') }}</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card-premium h-100 text-center " style="background-color: var(--brand-lime);">
                        <div class="bg-brand-mint text-brand-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-calculator fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.step_1_title') }}</h4>
                        <p class="text-dark opacity-75 small">{{ __('messages.step_1_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-premium h-100 text-center" style="background-color: var(--brand-lime);">
                        <div class="bg-brand-mint text-brand-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-person-check fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.step_2_title') }}</h4>
                        <p class="text-dark opacity-75 small">{{ __('messages.step_2_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-premium h-100 text-center" style="background-color: var(--brand-lime);">
                        <div class="bg-brand-mint text-brand-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-credit-card fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.step_3_title') }}</h4>
                        <p class="text-dark opacity-75 small">{{ __('messages.step_3_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-premium h-100 text-center" style="background-color: var(--brand-lime);">
                        <div class="bg-brand-mint text-brand-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-check-circle-fill fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.step_4_title') }}</h4>
                        <p class="text-dark opacity-75 small">{{ __('messages.step_4_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Core Features -->
    <section id="features" class="bg-white">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag">{{ __('messages.features') }}</span>
                <h2 class="section-title text-dark">{{ __('messages.why_choose_us') }}</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-premium h-100 border-0 shadow-sm p-5" style="background: var(--white-home);">
                        <div class="text-brand-dark mb-4 d-inline-block">
                            <i class="bi bi-shield-lock-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.feature_1_title') }}</h4>
                        <p class="text-muted mb-0">{{ __('messages.feature_1_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-premium h-100 border-0 shadow-sm p-5" style="background: var(--white-home);">
                        <div class="text-brand-dark mb-4 d-inline-block">
                            <i class="bi bi-percent fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.feature_2_title') }}</h4>
                        <p class="text-muted mb-0">{{ __('messages.feature_2_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-premium h-100 border-0 shadow-sm p-5" style="background: var(--white-home);">
                        <div class="text-brand-dark mb-4 d-inline-block">
                            <i class="bi bi-geo-alt-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.feature_3_title') }}</h4>
                        <p class="text-muted mb-0">{{ __('messages.feature_3_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-premium h-100 border-0 shadow-sm p-5" style="background: var(--white-home);">
                        <div class="text-brand-dark mb-4 d-inline-block">
                            <i class="bi bi-lightning-charge-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.feature_4_title') }}</h4>
                        <p class="text-muted mb-0">{{ __('messages.feature_4_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-premium h-100 border-0 shadow-sm p-5" style="background: var(--white-home);">
                        <div class="text-brand-dark mb-4 d-inline-block">
                            <i class="bi bi-globe fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.feature_5_title') }}</h4>
                        <p class="text-muted mb-0">{{ __('messages.feature_5_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-premium h-100 border-0 shadow-sm p-5" style="background: var(--white-home);">
                        <div class="text-brand-dark mb-4 d-inline-block">
                            <i class="bi bi-headset fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3 text-dark">{{ __('messages.feature_6_title') }}</h4>
                        <p class="text-muted mb-0">{{ __('messages.feature_6_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Supported Countries -->
    <section id="countries" style="background-color: var(--brand-lime);">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag" style="background: var(--brand-dark); color: var(--brand-lime);">{{ __('messages.countries') }}</span>
                <h2 class="section-title text-dark">{{ __('messages.move_borders') }}</h2>
            </div>
            <div class="row row-cols-2 row-cols-md-4 g-4">
                @php
                    $countries = [
                        ['n' => 'Switzerland', 'f' => 'ch'], ['n' => 'India', 'f' => 'in'],
                        ['n' => 'United Kingdom', 'f' => 'gb'], ['n' => 'Europe', 'f' => 'eu'],
                        ['n' => 'USA', 'f' => 'us'], ['n' => 'UAE', 'f' => 'ae'],
                        ['n' => 'Philippines', 'f' => 'ph'], ['n' => 'Canada', 'f' => 'ca']
                    ];
                @endphp
                @foreach($countries as $c)
                <div class="col text-center">
                    <div class="card-premium py-4">
                        <span class="fi fi-{{ $c['f'] }} rounded h1 mb-3 d-block mx-auto"></span>
                        <h6 class="fw-bold mb-0">{{ $c['n'] }}</h6>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="bg-light">
        <div class="container">
            <div class="section-header text-center col-lg-8 mx-auto">
                <span class="section-tag">FAQ</span>
                <h2 class="section-title">{{ __('messages.faq') }}</h2>
            </div>
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6 mb-4">
                    <div class="accordion accordion-flush" id="faqAccordionLeft" >
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm" > 
                            <h2 class="accordion-header" >
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f1">
                                    {{ __('messages.q1') }}
                                </button>
                            </h2>
                            <div id="f1" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a1') }}</div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f2">
                                    {{ __('messages.q2') }}
                                </button>
                            </h2>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a2') }}</div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f3">
                                    {{ __('messages.q3') }}
                                </button>
                            </h2>
                            <div id="f3" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a3') }}</div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f4">
                                    {{ __('messages.q4') }}
                                </button>
                            </h2>
                            <div id="f4" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a4') }}</div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f5">
                                    {{ __('messages.q5') }}
                                </button>
                            </h2>
                            <div id="f5" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a5') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Column -->
                <div class="col-md-6 mb-4">
                    <div class="accordion accordion-flush" id="faqAccordionRight">
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f6">
                                    {{ __('messages.q6') }}
                                </button>
                            </h2>
                            <div id="f6" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a6') }}</div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f7">
                                    {{ __('messages.q7') }}
                                </button>
                            </h2>
                            <div id="f7" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a7') }}</div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f8">
                                    {{ __('messages.q8') }}
                                </button>
                            </h2>
                            <div id="f8" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a8') }}</div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f9">
                                    {{ __('messages.q9') }}
                                </button>
                            </h2>
                            <div id="f9" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a9') }}</div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f10">
                                    {{ __('messages.q10') }}
                                </button>
                            </h2>
                            <div id="f10" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
                                <div class="accordion-body p-4 pt-0 text-muted">{{ __('messages.a10') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-brand-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h3 class="fw-bold text-brand-lime mb-4">MODREMIT</h3>
                    <p class="opacity-50 small mb-4 pe-lg-5">
                        {{ __('messages.footer_desc') }}
                    </p>
                    <div class="d-flex gap-3 fs-4 text-brand-lime">
                        <i class="bi bi-facebook"></i><i class="bi bi-twitter-x"></i><i class="bi bi-linkedin"></i>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-4">{{ __('messages.company') }}</h6>
                    <ul class="list-unstyled opacity-50 small">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.about_us') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.careers') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.privacy_policy') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-4">{{ __('messages.support') }}</h6>
                    <ul class="list-unstyled opacity-50 small">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.help_center') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.contact_us') }}</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">{{ __('messages.security') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold mb-4">{{ __('messages.stay_updated') }}</h6>
                    <div class="input-group mb-3 premium-subscribe">
                        <input type="text" class="form-control bg-transparent border-secondary text-white py-3 ps-4" placeholder="{{ __('messages.email_placeholder') }}" style="border-radius: 12px 0 0 12px;">
                        <button class="btn btn-brand px-4 fw-bold" type="button" style="border-radius: 0 12px 12px 0;">{{ __('messages.subscribe') }}</button>
                    </div>
                </div>
            </div>
            <hr class="my-5 opacity-10">
            <div class="text-center opacity-50 small">
                © {{ date('Y') }} Modremit. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Corrected Multi-Dropdown Logic
            document.querySelectorAll('.currency-dropdown').forEach(dropdown => {
                const trigger = dropdown.querySelector('.currency-trigger');
                const menu = dropdown.querySelector('.currency-menu');
                const input = dropdown.querySelector('input[type="hidden"]');

                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Close other dropdowns
                    document.querySelectorAll('.currency-menu').forEach(m => {
                        if (m !== menu) m.style.display = 'none';
                    });
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                });

                menu.querySelectorAll('.currency-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const flag = this.querySelector('img').src;
                        const code = this.dataset.code;
                        const name = this.dataset.name;

                        trigger.querySelector('img').src = flag;
                        trigger.querySelector('.currency-code').innerText = code;
                        if (trigger.querySelector('.currency-name')) {
                            trigger.querySelector('.currency-name').innerText = name;
                        }
                        input.value = code;

                        // Update active state
                        menu.querySelectorAll('.currency-item').forEach(i => i.classList.remove('active'));
                        this.classList.add('active');

                        menu.style.display = 'none';
                        fetchQuote(); // Trigger rate update
                    });
                });
            });

            document.addEventListener('click', () => {
                document.querySelectorAll('.currency-menu').forEach(m => m.style.display = 'none');
            });

            // Enhanced Choices.js with Flags
            const cityChoicesItems = {
                'CHF': '🇨🇭', 'USD': '🇺🇸', 'EUR': '🇪🇺', 'GBP': '🇬🇧', 
                'INR': '🇮🇳', 'PHP': '🇵🇭', 'PKR': '🇵🇰'
            };

            const selectors = document.querySelectorAll('.currency-select');
            selectors.forEach(select => {
                new Choices(select, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    callbackOnCreateTemplates: function(template) {
                        return {
                            item: (classNames, data) => {
                                const flag = data.element.dataset.flag;
                                return template(`
                                    <div class="${classNames.item} ${data.highlighted ? classNames.highlightedState : classNames.itemSelectable}" data-item data-id="${data.id}" data-value="${data.value}">
                                        <span class="fi fi-${flag} me-2 rounded-1 shadow-sm"></span> <span class="fw-bold">${data.value}</span>
                                    </div>
                                `);
                            },
                            choice: (classNames, data) => {
                                const flag = data.element.dataset.flag;
                                const name = data.element.dataset.name;
                                return template(`
                                    <div class="${classNames.item} ${classNames.itemChoice} ${data.disabled ? classNames.itemDisabled : classNames.itemSelectable}" data-select-text="${this.config.itemSelectText}" data-choice data-id="${data.id}" data-value="${data.value}" ${data.disabled ? 'aria-disabled="true"' : 'role="option"'}>
                                        <div class="d-flex align-items-center py-1">
                                            <span class="fi fi-${flag} me-3 fs-5 rounded-1 shadow-sm border border-white border-opacity-25"></span>
                                            <div style="line-height: 1.1;">
                                                <div class="fw-bold text-dark">${data.value}</div>
                                                <div class="text-muted small" style="font-size: 0.75rem;">${name}</div>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            },
                        };
                    }
                });
            });

            // Converter Logic
            const sendInput = document.getElementById('send_amount');
            const receiveInput = document.getElementById('receive_amount');
            const fromSelect = document.getElementById('from_currency');
            const toSelect = document.getElementById('to_currency');
            const feeDisplay = document.getElementById('fee_amount');
            const rateDisplay = document.getElementById('fx_rate');
            const loader = document.getElementById('loader');

            async function fetchQuote() {
                loader.classList.remove('d-none');
                try {
                    const response = await fetch('/public/quote', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            amount: sendInput.value,
                            from: fromSelect.value,
                            to: toSelect.value
                        })
                    });
                    const data = await response.json();
                    
                    if (data.error) {
                        receiveInput.value = data.error;
                    } else {
                        receiveInput.value = data.result.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        feeDisplay.innerText = data.fee.toFixed(2);
                        rateDisplay.innerText = data.rate.toFixed(4);
                        document.querySelectorAll('.from_code').forEach(el => el.innerText = data.from);
                    }
                } catch (error) {
                    console.error('Quote error:', error);
                } finally {
                    loader.classList.add('d-none');
                }
            }

            [sendInput].forEach(el => {
                el.addEventListener('change', fetchQuote);
            });
            // We removed to_currency from the change list because we handle it in the custom dropdown click
            sendInput.addEventListener('input', debounce(fetchQuote, 500));

            function debounce(func, wait) {
                let timeout;
                return function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, arguments), wait);
                };
            }

            fetchQuote(); // Initial load
        });
    </script>
</body>
</html>
