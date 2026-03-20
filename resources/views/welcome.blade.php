<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modremit - Global Money Transfers | Fast, Secure & Transparent</title>
    
    <!-- Fonts & Icons -->
    <link href="{{ asset('vendor/css/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/flag-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/brand.css') }}" rel="stylesheet">
    
    <!-- Choices.js for Searchable Select -->
    <link rel="stylesheet" href="{{ asset('vendor/css/choices.min.css') }}" />

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
            max-width: 720px;
            margin: 40px auto 0 auto;
            font-size: 22px;
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
        .choices {
            margin-bottom: 0 !important;
        }
        .choices__inner {
            background: transparent !important;
            border: none !important;
            padding: 0 10px !important;
            min-height: 48px !important;
            display: flex;
            align-items: center;
        }
        .choices__list--single {
            padding: 0 !important;
            width: 100%;
        }
        .choices__item {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--brand-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .choices[data-type*="select-one"]::after {
            right: 15px;
            margin-top: -6px;
            border-color: var(--brand-dark) transparent transparent transparent;
        }
        .choices__list--dropdown {
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            padding: 8px;
            width: 300px !important;
            right: 0;
            left: auto;
            word-wrap: break-word;
        }
        .choices__list--dropdown .choices__item--selectable {
            border-radius: 12px;
            padding: 12px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: var(--gray-light);
            color: var(--brand-dark);
        }
        .choices__item .currency-name {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            white-space: normal;
        }

        .choices__item img.flag-icon, 
        .choices__item img {
            max-width: 24px !important;
            min-width: 24px !important;
            width: 24px !important;
            height: 18px !important;
            max-height: 18px !important;
            object-fit: cover !important;
            display: block !important;
            border-radius: 2px !important;
            flex-shrink: 0 !important;
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

        /* Modernized Choices.js for Currency Dropdowns */
        .choices[data-type*="select-one"]::after {
            right: 15px;
            margin-top: -6px;
            border-color: var(--brand-dark) transparent transparent transparent;
        }
        .choices__inner {
            background: transparent !important;
            border: none !important;
            padding: 0 10px !important;
            min-height: 48px !important;
            display: flex;
            align-items: center;
        }
        .choices__list--single {
            padding: 0 !important;
            width: 100%;
        }
        .choices__item {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--brand-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .choices__list--dropdown {
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            padding: 8px;
            width: 300px !important;
            right: 0;
            left: auto;
            word-wrap: break-word; /* Fixes German overflowing text */
        }
        .choices__list--dropdown .choices__item--selectable {
            border-radius: 12px;
            padding: 12px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: var(--gray-light);
            color: var(--brand-dark);
        }
        .choices__item .currency-name {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            white-space: normal;
        }

        /* Responsive Fixes */
        @media (max-width: 991px) {
            .hero-title { 
                font-size: clamp(2.5rem, 8vw, 3.5rem) !important; 
                letter-spacing: -1.5px !important;
                line-height: 1.1;
            }
            .hero { padding-bottom: 60px; }
            .converter-pod { margin-top: 30px; }
        }

        @media (max-width: 575px) {
            .hero-title { font-size: 2.2rem !important; }
            .converter-pod { padding: 20px !important; }
            .amount-input { font-size: 1.25rem !important; }
            .pod-label { font-size: 0.7rem; }
            .section-title { font-size: 1.8rem; }
        }

        /* Typewriter Cursor */
        .typewriter-text {
            border-right: 4px solid var(--brand-lime);
            padding-right: 4px;
            animation: blink 0.75s step-end infinite;
        }

        @keyframes blink {
            from, to { border-color: transparent; }
            50% { border-color: var(--brand-lime); }
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
                        <li class="nav-item"><a class="nav-link text-muted opacity-75" href="{{ route('login') }}"><i class="bi bi-person-badge me-1"></i> {{ __('messages.agent_portal') }}</a></li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <div class="lang-switcher d-flex me-4">
                            <a href="{{ route('lang.switch', 'en') }}" class="nav-link px-2 {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                            <span class="text-muted">|</span>
                            <a href="{{ route('lang.switch', 'de') }}" class="nav-link px-2 {{ app()->getLocale() == 'de' ? 'active' : '' }}">DE</a>
                        </div>
                        <a href="{{ route('customer.login') }}" class="nav-link me-3">{{ __('messages.login') }}</a>
                        <a href="{{ route('customer.register') }}" class="btn-brand text-decoration-none">{{ __('messages.register') }}</a>
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
                            <i class="bi bi-lightning-charge-fill me-1 text-warning"></i>
                            1 CHF = <span id="hero_rate_amount">{{ number_format($baseRate ?? 96.85, 2) }}</span> <span id="hero_rate_currency">INR</span> <span class="fi fi-in fi-badge me-1" id="hero_rate_flag" style="border-radius:3px;"></span>
                        </span>
                    </div>
                    <h1 class="hero-title">{!! __('messages.hero_title') !!}</h1>
                    <p class="fs-5 text-dark-emphasis mb-5 pe-lg-5">
                        {{ __('messages.hero_subtitle') }}
                    </p>
                    <div class="d-flex gap-3 mb-5">
                        <a href="#converter" class="btn-brand px-5 py-3 fs-5 text-decoration-none">{{ __('messages.send_money') }}</a>
                        <a href="{{ route('customer.register') }}" class="btn btn-brand-outline px-5 py-3 fs-5 text-decoration-none">{{ __('messages.get_started') }}</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <!-- Converter Pod -->
                    <div class="converter-pod" id="converter">
                        <div class="mb-4">
                            <label class="pod-label">{{ __('messages.you_send') }}</label>
                            <div class="input-group-modern">
                                <input type="number" id="send_amount" class="amount-input" value="1000">
                                <div style="width: 140px;">
                                    <select id="from_currency" class="choices" name="from_currency">
                                        @foreach(\App\Constants\CountryCurrency::CURRENCIES as $c)
                                            <option value="{{ $c['code'] }}" data-name="{{ $c['name'] }}" data-flag="{{ $c['flag'] }}" {{ $c['code'] === 'CHF' ? 'selected' : '' }}>
                                                {{ $c['code'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative my-4 ps-4 border-start border-2" style="border-color: var(--brand-lime) !important;">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold"><i class="bi bi-dash"></i> <span id="fee_amount">15.00</span> <span class="from_code">CHF</span></span>
                                <span class="text-muted small">{{ __('messages.fee') }}</span>
                            </div>
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold"><i class="bi bi-x"></i> <span id="fx_rate">{{ number_format($baseRate ?? 96.85, 4) }}</span></span>
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
                                <div style="width: 140px;">
                                    <select id="to_currency" class="choices" name="to_currency">
                                        @foreach(\App\Constants\CountryCurrency::CURRENCIES as $c)
                                            <option value="{{ $c['code'] }}" data-name="{{ $c['name'] }}" data-flag="{{ $c['flag'] }}" {{ $c['code'] === 'INR' ? 'selected' : '' }}>
                                                {{ $c['code'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
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
                <p class="mb-3 text-light small">"{{ $review['text'] }}"</p>
                <div class="d-flex align-items-center">
                    <span class="fi fi-{{ $review['country'] }} me-2 rounded-circle border"></span>
                    <span class="small fw-bold text-light"> {{ $review['name'] }}</span>
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
                <div class="col-lg-6">

                    <a class="navbar-brand d-flex align-items-center {{ $class ?? '' }}" href="{{ url('/') }}">
                        
                        <div class="bg-brand-light text-brand-dark rounded-3 d-flex align-items-center justify-content-center me-3"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-send-fill" style="font-size: 1.4rem;"></i>
                        </div>

                        <span class="fw-bold text-light" style="font-size: 1.5rem; letter-spacing: -0.5px;">
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
    <script src="{{ asset('vendor/js/choices.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ─── Typewriter Effect ────────────────────────────────────────────────
            const words = "{!! __('messages.hero_typewriter_words') !!}".split(',');
            const textElement = document.querySelector('.typewriter-text');
            
            if (textElement && words.length > 0) {
                let wordIndex = 0;
                let charIndex = 0;
                let isDeleting = false;
                
                function type() {
                    const currentWord = words[wordIndex];
                    
                    if (isDeleting) {
                        textElement.textContent = currentWord.substring(0, charIndex - 1);
                        charIndex--;
                    } else {
                        textElement.textContent = currentWord.substring(0, charIndex + 1);
                        charIndex++;
                    }
                    
                    let typeSpeed = isDeleting ? 50 : 100;
                    
                    if (!isDeleting && charIndex === currentWord.length) {
                        typeSpeed = 2000; // Pause at end of word
                        isDeleting = true;
                    } else if (isDeleting && charIndex === 0) {
                        isDeleting = false;
                        wordIndex = (wordIndex + 1) % words.length;
                        typeSpeed = 500; // Pause before new word
                    }
                    
                    setTimeout(type, typeSpeed);
                }
                
                // Start effect
                setTimeout(type, 1000);
            }

            // ─── Currency Dropdown Logic (Choices.js) ──────────────────────────────
            
            // Helper function to safely extract custom properties
            function getProps(data) {
                if (data.element && data.element.dataset) {
                    return {
                        name: data.element.dataset.name || data.value,
                        flag: data.element.dataset.flag || 'xx'
                    };
                }
                return null;
            }

            const choicesTemplate = function(template, data) {
                const c = getProps(data);
                if (!c) {
                    return template(`<div class="choices__item choices__item--choice" data-select-text="" data-choice data-id="${data.id}" data-value="${data.value}">${data.label}</div>`);
                }
                
                const flagUrl = `https://flagcdn.com/${c.flag}.svg`;
                return template(`
                  <div class="choices__item choices__item--choice choices__item--selectable" data-select-text="" data-choice ${data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable'} data-id="${data.id}" data-value="${data.value}">
                    <div style="display: flex; align-items: center; gap: 10px; width: 100%;">
                      <span class="fi fi-${c.flag} fi-badge" style="font-size: 1.25rem; border-radius: 3px; flex-shrink: 0; box-shadow: 0 0 1px rgba(0,0,0,0.5);"></span>
                      <div style="display: flex; flex-direction: column; line-height: 1.2;">
                          <span style="font-size: 1rem; font-weight: 700; color: var(--brand-dark);">${data.label}</span>
                          <span style="font-size: 0.75rem; font-weight: 500; color: #6c757d;">${c.name}</span>
                      </div>
                    </div>
                  </div>
                `);
            };

            const choicesItemTemplate = function(template, data) {
                const c = getProps(data);
                if (!c) {
                    return template(`<div class="choices__item" data-item data-id="${data.id}" data-value="${data.value}">${data.label}</div>`);
                }
                const flagUrl = `https://flagcdn.com/${c.flag}.svg`;
                return template(`
                    <div class="choices__item choices__item--selectable" data-item data-id="${data.id}" data-value="${data.value}">
                      <div style="display: flex; align-items: center; gap: 10px; width: 100%;">
                        <span class="fi fi-${c.flag} fi-badge" style="font-size: 1.25rem; border-radius: 3px; flex-shrink: 0; box-shadow: 0 0 1px rgba(0,0,0,0.5);"></span>
                        <span style="font-weight: 700; color: var(--brand-dark);">${data.label}</span>
                      </div>
                    </div>
                `);
            };

            // Initialize both dropdowns
            const choicesConfig = {
                searchEnabled: true,
                searchPlaceholderValue: 'Search currency...',
                itemSelectText: '',
                shouldSort: false, // Keep predefined order
                callbackOnCreateTemplates: function(template) {
                    return {
                        item: (classNames, data) => choicesItemTemplate(template, data),
                        choice: (classNames, data) => choicesTemplate(template, data),
                    };
                }
            };

            const fromSelectEl = document.getElementById('from_currency');
            const toSelectEl   = document.getElementById('to_currency');

            let fromChoices = null;
            let toChoices = null;

            // Use a safer initialization block
            function initChoices() {
                if (typeof Choices === 'undefined') {
                    console.error("Choices.js library not loaded. Retrying in 500ms...");
                    setTimeout(initChoices, 500);
                    return;
                }

                try {
                    if (fromSelectEl) {
                        fromChoices = new Choices(fromSelectEl, choicesConfig);
                    }
                    if (toSelectEl) {
                        toChoices = new Choices(toSelectEl, choicesConfig);
                    }
                    console.log("Choices.js initialized successfully");
                } catch (err) {
                    console.error("Choices.js Initialization Error:", err);
                }
            }

            initChoices();

            const currencyState = {
                from: fromSelectEl ? fromSelectEl.value : 'CHF',
                to:   toSelectEl ? toSelectEl.value : 'INR',
            };

            if (fromSelectEl) {
                fromSelectEl.addEventListener('change', function(e) {
                    currencyState.from = e.target.value;
                    // When changing 'from' currency, recalculate based on current 'send' amount
                    fetchQuote('send');
                });
            }

            if (toSelectEl) {
                toSelectEl.addEventListener('change', function(e) {
                    currencyState.to = e.target.value;
                    // When changing 'to' currency, recalculate based on current 'send' amount
                    fetchQuote('send');
                });
            }


            // ─── Converter Quote Logic ─────────────────────────────────────────────
            const sendInput    = document.getElementById('send_amount');
            const receiveInput = document.getElementById('receive_amount');
            const feeDisplay   = document.getElementById('fee_amount');
            const rateDisplay  = document.getElementById('fx_rate');
            const loader       = document.getElementById('loader');

            // Track which field was last edited to maintain two-way binding
            let lastEditedField = 'send'; 

            async function fetchQuote(triggerField = null) {
                if (triggerField) lastEditedField = triggerField;

                const amountVal = lastEditedField === 'send' ? sendInput.value : receiveInput.value;
                const amount = parseFloat(amountVal.replace(/,/g, '')) || 0;
                
                if (amount < 1) return;

                if (loader) loader.classList.remove('d-none');

                try {
                    const res = await fetch('/public/quote', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            amount: amount,
                            from:   currencyState.from,
                            to:     currencyState.to,
                            amount_type: lastEditedField === 'send' ? 'send' : 'receive'
                        })
                    });

                    if (!res.ok) throw new Error('Network error ' + res.status);
                    const data = await res.json();

                    if (data.error) {
                        // Handle error
                        console.warn('Quote error:', data.error);
                    } else {
                        // Update the OTHER field
                        if (lastEditedField === 'send') {
                            if (receiveInput) {
                                receiveInput.value = parseFloat(data.target_amount).toLocaleString(undefined, {
                                    minimumFractionDigits: 2, maximumFractionDigits: 2
                                });
                            }
                        } else {
                            if (sendInput) {
                                sendInput.value = parseFloat(data.amount).toFixed(2);
                            }
                        }

                        // Update fee and rate displays
                        if (feeDisplay) feeDisplay.innerText = parseFloat(data.fee || 0).toFixed(2);
                        if (rateDisplay) {
                            rateDisplay.innerText = parseFloat(data.rate || 0).toFixed(4);
                        }
                        // Update from_code labels
                        document.querySelectorAll('.from_code').forEach(el => el.innerText = data.from || currencyState.from);
                    }
                } catch (err) {
                    console.error('FX Quote Error:', err);
                } finally {
                    if (loader) loader.classList.add('d-none');
                }
            }

            // Debounce helper
            function debounce(fn, ms) {
                let timer;
                return (...args) => { clearTimeout(timer); timer = setTimeout(() => fn(...args), ms); };
            }

            // Trigger on amount change (Two-way)
            if (sendInput) {
                sendInput.addEventListener('input', debounce(() => fetchQuote('send'), 600));
            }
            if (receiveInput) {
                receiveInput.removeAttribute('readonly'); // Enable editing
                receiveInput.addEventListener('input', debounce(() => fetchQuote('receive'), 600));
            }

            // Initial load
            fetchQuote('send');


            // ─── Hero Badge Cycling ────────────────────────────────────────────────
            const heroRates = [
                { code: 'INR', rate: {{ $baseRate ?? 96.85 }}, flag: 'in' },
                { code: 'USD', rate: 1.12,   flag: 'us' },
                { code: 'CAD', rate: 1.52,   flag: 'ca' },
                { code: 'EUR', rate: 1.04,   flag: 'eu' },
                { code: 'GBP', rate: 0.89,   flag: 'gb' },
                { code: 'PHP', rate: 64.10,  flag: 'ph' },
                { code: 'PKR', rate: 311.50, flag: 'pk' },
            ];

            let heroIdx = 0;
            const heroAmountEl   = document.getElementById('hero_rate_amount');
            const heroCurrencyEl = document.getElementById('hero_rate_currency');
            const heroFlagEl     = document.getElementById('hero_rate_flag');

            if (heroAmountEl && heroCurrencyEl) {
                heroAmountEl.style.transition   = 'opacity 0.3s ease';
                heroCurrencyEl.style.transition = 'opacity 0.3s ease';
                if (heroFlagEl) heroFlagEl.style.transition = 'opacity 0.3s ease';

                setInterval(() => {
                    heroIdx = (heroIdx + 1) % heroRates.length;
                    const entry = heroRates[heroIdx];

                    heroAmountEl.style.opacity   = '0';
                    heroCurrencyEl.style.opacity = '0';
                    if (heroFlagEl) heroFlagEl.style.opacity = '0';

                    setTimeout(() => {
                        heroAmountEl.textContent   = entry.rate.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        heroCurrencyEl.textContent = entry.code;
                        if (heroFlagEl) {
                            heroFlagEl.className      = `fi fi-${entry.flag} fi-badge me-1`;
                            heroFlagEl.style.opacity  = '1';
                        }
                        heroAmountEl.style.opacity   = '1';
                        heroCurrencyEl.style.opacity = '1';
                    }, 300);
                }, 2000);
            }

        });
    </script>
</body>
</html>

