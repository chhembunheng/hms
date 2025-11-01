<!DOCTYPE html>
<html lang="{{ config('app.language_variant')[app()->getLocale()] ?? 'en-US' }}" dir="ltr">

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

    <!-- Core CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('site/assets/css/meanmenu.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('site/assets/css/style.min.css') . (env('APP_ENV') == 'local' || request()->clear == 'yes' ? '?v=' . time() : '') }}" />
    <link rel="stylesheet" href="{{ asset('site/assets/css/responsive.min.css') . (env('APP_ENV') == 'local' || request()->clear == 'yes' ? '?v=' . time() : '') }}" />
    @include('frontend.layouts.partials.head')
</head>

<body class="antialiased">
    <section class="error-area">
        <div class="d-table">
            <div class="d-table-cell">
                <div class="container">
                    <div class="error-content">
                        <img src="@yield('code', webpasset('site/assets/img/404.png'))" alt="error">
                        <h3>@yield('title', __('global.error_404_title'))</h3>
                        <p>@yield('message', __('global.error_404_message'))</p>
                        <a href="{{ route('frontend.home', ['locale' => app()->getLocale() ?? 'en']) }}" class="default-btn-one">{{ __('global.back_to_home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
