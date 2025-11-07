<!DOCTYPE html>
<html lang="{{ config('init.language_variants')[app()->getLocale()] ?? 'en-US' }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Wintech is a leading IT solutions provider, offering innovative software and technology services to empower businesses worldwide.">
    <meta name="keywords" content="IT solutions, software development, technology services, business solutions, Wintech">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow">
    <title>@yield('title', config('app.name'))</title>

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- Favicon / PWA -->
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="apple-mobile-web-app-title" content="WinTech">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#ffffff">



    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og:title', config('app.name'))">
    <meta property="og:description" content="@yield('og:description', 'Wintech is a leading IT solutions provider, offering innovative software and technology services to empower businesses worldwide.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="@yield('og:url', request()->url())">
    <meta property="og:image" content="@yield('og:image', asset('assets/images/seo/og.jpg'))">
    <meta property="og:site_name" content="@yield('og:site_name', config('app.name'))">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter:title', config('app.name'))">
    <meta name="twitter:description" content="@yield('twitter:description', 'Wintech is a leading IT solutions provider, offering innovative software and technology services to empower businesses worldwide.')">
    <meta name="twitter:image" content="@yield('twitter:image', asset('assets/images/seo/og.jpg'))">

    <!-- Hreflang -->
    @if (isset($locale) && $locale !== 'en')
        <link rel="alternate" hreflang="en" href="{{ str_replace('/' . $locale, '', request()->url()) }}" />
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ request()->url() }}" />
    @else
        <link rel="alternate" hreflang="{{ $locale ?? 'en' }}" href="{{ request()->url() }}" />
    @endif

    <!-- Core CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="{{ asset('site/assets/css/meanmenu.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('site/assets/css/style.min.css') . (env('APP_ENV') == 'local' || request()->clear == 'yes' ? '?v=' . time() : '') }}" />
    <link rel="stylesheet" href="{{ asset('site/assets/css/responsive.min.css') . (env('APP_ENV') == 'local' || request()->clear == 'yes' ? '?v=' . time() : '') }}" />
    @include('frontends.layouts.partials.head')
</head>

<body>
    @php
        $img = webp_variants('assets/logo/full-blue.png', 'bxs', null, 60);
    @endphp
    <header id="header" role="banner" class="navbar-area is-sticky">
        <div class="techone-responsive-nav">
            <div class="techone-responsive-menu">
                <div class="logo">
                    <a href="{{ Route::currentRouteName() == 'welcome' ? '#' : route('welcome', ['locale' => app()->getLocale()]) }}">
                        <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 600px) 95vw, {{ $img['width'] }}px" alt="Brand Name Logo" loading="lazy" width="{{ $img['width'] }}" height="auto">
                    </a>
                </div>
            </div>
        </div>
        <nav id="main-nav" class="techone-nav" role="navigation" aria-label="Main Navigation">
            <div class="container">
                <nav class="navbar navbar-expand-md navbar-light desktop-screen">
                    <a class="navbar-brand" href="{{ Route::currentRouteName() == 'welcome' ? '#' : route('welcome', ['locale' => app()->getLocale()]) }}">
                        <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 600px) 95vw, {{ $img['width'] }}px" alt="Brand Name Logo" loading="lazy" width="{{ $img['width'] }}" height="auto">
                    </a>
                    <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item switch-language-mobile">
                                @php
                                    $languages = collect(config('init.languages'));
                                    $currentLocale = app()->getLocale();
                                    $currentLanguage = $languages->firstWhere('code', $currentLocale);
                                @endphp
                                <a href="#" class="nav-link">
                                    <img src="{{ asset($currentLanguage['flag']) }}" class="language-flag" alt="Language Flag">
                                    {{ $currentLanguage['name'] }}
                                    <i class="fa-solid fa-chevron-down fa-fw desktop-icon"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach (config('init.languages') as $language)
                                        @if ($language['code'] !== $currentLocale)
                                            @php
                                                $switchUrl = route(Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => $language['code']]));
                                            @endphp
                                            <li class="nav-item">
                                                <a href="{{ $switchUrl }}" class="nav-link" aria-label="{{ __('global.switch_language_to', ['lang' => $language['name']]) }}">
                                                    <img src="{{ asset($language['flag']) }}" class="language-flag">
                                                    &nbsp;&nbsp;{{ $language['name'] }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                            @foreach ($navigations ?? [] as $navigation)
                                @include('frontends.layouts.partials.navigation-item', ['navigation' => $navigation])
                            @endforeach
                        </ul>
                        <div class="other-option">
                            <ul class="navbar-nav">
                                <li class="nav-item switch-language-desktop d-flex align-items-center">
                                    @php
                                        $languages = collect(config('init.languages'));
                                        $currentLocale = app()->getLocale();
                                        $currentLanguage = $languages->firstWhere('code', $currentLocale);
                                    @endphp
                                    <a href="#" class="nav-link text-nowrap">
                                        <img src="{{ asset($currentLanguage['flag']) }}" class="language-flag" alt="Language Flag">
                                        {{ $currentLanguage['name'] }}
                                        <i class="fa-solid fa-chevron-down fa-fw desktop-icon"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @foreach (config('init.languages') as $language)
                                            @if ($language['code'] !== $currentLocale)
                                                @php
                                                    $switchUrl = route(Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => $language['code']]));
                                                @endphp
                                                <li class="nav-item">
                                                    <a href="{{ $switchUrl }}" class="nav-link" aria-label="{{ __('global.switch_language_to', ['lang' => $language['name']]) }}">
                                                        <img src="{{ asset($language['flag']) }}" class="language-flag">
                                                        &nbsp;&nbsp;{{ $language['name'] }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </nav>
    </header>
    <main id="main-content" role="main">
        {{ $slot }}
    </main>
    <footer id="footer" role="contentinfo">
        @include('frontends.layouts.partials.footer')
    </footer>
    <button class="go-top" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
        <i class="fas fa-chevron-up"></i>
    </button>
    <style>
        #cookieBannerWrapper {
            position: fixed;
            left: 1rem;
            bottom: 1rem;
            z-index: 9999;
            max-width: min(360px, 90vw);
            width: 100%
        }

        @media(max-width:480px) {
            #cookieBannerWrapper {
                left: 0;
                right: 0;
                bottom: 0;
                max-width: 100%;
                padding: 0 .75rem .75rem
            }
        }

        .chevron-rotated {
            transform: rotate(180deg)
        }

        .chevron-icon {
            transition: transform .2s ease;
            display: inline-block
        }

        .text-body-secondary a {
            text-decoration: underline;
            text-underline-offset: 2px
        }
    </style>
    <div id="cookieBannerWrapper" class="d-none">
        <div class="card shadow-lg border rounded-3" id="cookieBanner" role="dialog" aria-labelledby="cookieTitle" aria-describedby="cookieDesc">
            <div class="card-body">
                <div class="card-title mb-2 d-flex align-items-center gap-2" id="cookieTitle"><span class="fw-semibold">{{ __('global.we_use_cookies') }}</span><span class="badge bg-primary rounded-pill">{{ __('global.privacy') }}</span></div>
                <div id="cookieDesc" class="small text-body-secondary">
                    <p class="mb-2">{{ __('global.cookie_intro_text') }}</p>
                    <p class="mb-3">{{ __('global.cookie_choice_text') }} <a class="link-primary" href="{{ Route::has('cookie-policy') ? route('cookie-policy', ['locale' => app()->getLocale()]) : '#' }}" target="_blank"
                            rel="noopener noreferrer">{{ __('global.cookie_policy') }}</a> {{ __('global.for_details') }}</p><button class="btn btn-link p-0 small text-body-secondary d-inline-flex align-items-center gap-1" type="button" id="detailsToggle" aria-expanded="false"
                        aria-controls="cookieDetails"><span id="chevron" class="chevron-icon">⌄</span><span>{{ __('global.show_cookie_categories') }}</span></button>
                    <div class="mt-2 collapse" id="cookieDetails" style="display:none;">
                        <div class="border rounded-2 p-2 bg-body-tertiary small">
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="fw-semibold text-body">{{ __('global.strictly_necessary') }}</div>
                                <div>{{ __('global.strictly_necessary_desc') }}</div>
                            </div>
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="fw-semibold text-body">{{ __('global.analytics') }}</div>
                                <div>{{ __('global.analytics_desc') }}</div>
                            </div>
                            <div>
                                <div class="fw-semibold text-body">{{ __('global.personalization_ads') }}</div>
                                <div>{{ __('global.personalization_ads_desc') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-3"><button class="btn btn-dark flex-fill" id="essentialBtn">{{ __('global.only_necessary') }}</button>
                    <button class="btn btn-outline-secondary flex-fill" id="declineBtn">{{ __('global.decline_tracking') }}</button>
                    <button class="btn btn-primary flex-fill" id="acceptAllBtn">{{ __('global.accept_all_cookies') }}</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/counterup2/2.0.2/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/jquery.validate.min.js"></script>
    <script src="{{ asset('site/assets/js/jquery.meanmenu.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/mail.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/main.min.js') }}"></script>
    @include('frontends.layouts.partials.js')
    @stack('scripts')
</body>

</html>
