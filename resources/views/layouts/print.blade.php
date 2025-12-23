@php
    $systemConfig = \App\Models\Settings\SystemConfiguration::first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $systemConfig ? $systemConfig->system_title : config('app.name'))</title>

    <!-- Favicon -->
    @if($systemConfig && $systemConfig->favicon_path)
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($systemConfig->favicon_path) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ Storage::url($systemConfig->favicon_path) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Bootstrap CSS for print -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @media print {
            body {
                font-size: 12px;
            }
            .no-print {
                display: none !important;
            }
        }
        body {
            font-size: 14px;
        }
    </style>

    @stack('css')
</head>
<body>
    <div class="container-fluid">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Trigger print dialog on page load
        window.onload = function() {
            window.print();
        };
    </script>
    @stack('js')
</body>
</html>
