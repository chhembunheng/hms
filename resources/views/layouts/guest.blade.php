<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script>
        // Prevent FOUC (Flash of Unstyled Content) by setting theme before page renders
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Ubuntu:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css?' . time()) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dark-mode.css?' . time()) }}">
</head>
<body class="text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    {{ $slot }}
</body>
</html>
