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
            background-color: var(--white);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Hero Styling */
        .hero {
            background-color: var(--brand-lime);
            padding: 40px 0 100px 0;
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
            background: var(--white);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            max-width: 520px;
            margin: 40px auto 0 auto;
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
            color: var(--white);
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .marquee:hover .marquee-content {
            animation-play-state: paused;
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
        .lang-switcher .active {
            background: rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="hero">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">MODREMIT</a>
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

        <div class="container pt-5 mt-4">
            <div class="row align-items-center">
                <div class="col-lg-7">
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
                                <select id="from_currency" class="currency-select">
                                    <option value="CHF" data-flag="ch" selected>CHF</option>
                                    <option value="USD" data-flag="us">USD</option>
                                    <option value="EUR" data-flag="eu">EUR</option>
                                    <option value="GBP" data-flag="gb">GBP</option>
                                </select>
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
                                <select id="to_currency" class="currency-select">
                                    <option value="INR" data-flag="in" selected>INR</option>
                                    <option value="EUR" data-flag="eu">EUR</option>
                                    <option value="USD" data-flag="us">USD</option>
                                    <option value="GBP" data-flag="gb">GBP</option>
                                    <option value="PHP" data-flag="ph">PHP</option>
                                    <option value="PKR" data-flag="pk">PKR</option>
                                </select>
                            </div>
                        </div>

                        <div id="loader" class="text-center d-none mb-3">
                            <div class="spinner-border spinner-border-sm text-success" role="status"></div>
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
    <section id="how-it-works" class="bg-light">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag">{{ __('messages.how_it_works') }}</span>
                <h2 class="section-title">Transparent & Simple</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card-premium h-100 text-center">
                        <div class="bg-brand-mint text-brand-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-calculator fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">1. Check Rates</h4>
                        <p class="text-muted small">Choose currencies and see our guaranteed live rates and low fees.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-premium h-100 text-center">
                        <div class="bg-brand-mint text-brand-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-person-check fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">2. Register</h4>
                        <p class="text-muted small">Create a free account in minutes. Securely verify your identity.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-premium h-100 text-center">
                        <div class="bg-brand-mint text-brand-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-credit-card fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">3. Pay & Send</h4>
                        <p class="text-muted small">Pay via bank transfer or card. We'll start the conversion instantly.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-premium h-100 text-center">
                        <div class="bg-brand-mint text-brand-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-check-circle-fill fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">4. Arrives!</h4>
                        <p class="text-muted small">The recipient gets the money directly in their local currency.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Supported Countries -->
    <section id="countries">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-tag">{{ __('messages.countries') }}</span>
                <h2 class="section-title">We move money across borders</h2>
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
                <div class="col-lg-8 mx-auto">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f1">
                                    How long does a transfer take?
                                </button>
                            </h2>
                            <div id="f1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body p-4 pt-0 text-muted">
                                    Most transfers arrive within minutes. Some local bank settlements might take up to 24 hours depending on the destination.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item card-premium mb-3 bg-white p-0 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold p-4 bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#f2">
                                    Are my funds secure?
                                </button>
                            </h2>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body p-4 pt-0 text-muted">
                                    Absolutely. We use bank-grade encryption and are fully regulated in all operating jurisdictions.
                                </div>
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
                    <h6 class="fw-bold mb-4">Company</h6>
                    <ul class="list-unstyled opacity-50 small">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Careers</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-4">Support</h6>
                    <ul class="list-unstyled opacity-50 small">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Contact Us</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Security</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold mb-4">Stay Updated</h6>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control bg-transparent border-secondary text-white" placeholder="Email address">
                        <button class="btn btn-brand" type="button">Subscribe</button>
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
                    callbackOnCreateTemplates: function(template) {
                        return {
                            item: (classNames, data) => {
                                const flag = data.customProperties && data.customProperties.flag ? data.customProperties.flag : data.element.dataset.flag;
                                return template(`
                                    <div class="${classNames.item} ${data.highlighted ? classNames.highlightedState : classNames.itemSelectable}" data-item data-id="${data.id}" data-value="${data.value}">
                                        <span class="fi fi-${flag} me-2"></span> ${data.label}
                                    </div>
                                `);
                            },
                            choice: (classNames, data) => {
                                const flag = data.customProperties && data.customProperties.flag ? data.customProperties.flag : data.element.dataset.flag;
                                return template(`
                                    <div class="${classNames.item} ${classNames.itemChoice} ${data.disabled ? classNames.itemDisabled : classNames.itemSelectable}" data-select-text="${this.config.itemSelectText}" data-choice data-id="${data.id}" data-value="${data.value}" ${data.disabled ? 'aria-disabled="true"' : 'role="option"'}>
                                        <span class="fi fi-${flag} me-2"></span> ${data.label}
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

            [sendInput, fromSelect, toSelect].forEach(el => {
                el.addEventListener('change', fetchQuote);
            });
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
