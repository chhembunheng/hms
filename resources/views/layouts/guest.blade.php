@php
    $systemConfig = \App\Models\Settings\SystemConfiguration::first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $systemConfig ? $systemConfig->system_title : config('app.name', 'Hotel Management System') }}</title>
    @if($systemConfig && $systemConfig->favicon_path)
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($systemConfig->favicon_path) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ Storage::url($systemConfig->favicon_path) }}">
    @endif
    <script>
        // Prevent FOUC by setting theme before page renders
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Hanuman:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css?' . time()) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dark-mode.css?' . time()) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/hotel-login.css?' . time()) }}">
    <style>
        /* Local Outfit Font Definitions */
        @font-face {
          font-family: 'Outfit';
          font-style: normal;
          font-weight: 300 700;
          font-display: swap;
          src: url('{{ asset("assets/fonts/outfit/outfit-latin.woff2") }}') format('woff2');
          unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
          font-family: 'Outfit';
          font-style: normal;
          font-weight: 300 700;
          font-display: swap;
          src: url('{{ asset("assets/fonts/outfit/outfit-latin-ext.woff2") }}') format('woff2');
          unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        html, body, button, input, select, textarea, .form-control, .form-label, h1, h2, h3, h4, h5, h6, p, span, a {
            font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
        }
    </style>
</head>
<body class="text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    {{ $slot }}

    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const icon = document.getElementById('theme-toggle-icon');
            if (icon) {
                if (document.documentElement.classList.contains('dark')) {
                    icon.className = 'fa-solid fa-sun';
                } else {
                    icon.className = 'fa-solid fa-moon';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcon);
    </script>
</body>
</html>

