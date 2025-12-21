<!DOCTYPE html>
@php
    $systemConfig = \App\Models\Settings\SystemConfiguration::first();
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-locale="{{ app()->getLocale() }}" dir="ltr" translate="no">

<head>
    <meta charset="utf-8">
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-translate-customization" content="..."/>
    <meta name="robots" content="noarchive">
    <title>{{ $systemConfig ? $systemConfig->system_title : config('app.name') }}</title>

    <!-- Favicon -->
    @if($systemConfig && $systemConfig->favicon_path)
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($systemConfig->favicon_path) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ Storage::url($systemConfig->favicon_path) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Ensure locale is properly set for font loading
        document.documentElement.setAttribute('data-locale', '{{ app()->getLocale() }}');
    </script>
    @include('layouts.partials.style')
    @stack('css')
    <style>
        .multiple-select {
            width: 100% !important;
            max-width: 100% !important;
            visibility: hidden;
            height: 0 !important;
        }

        /* Enhanced DataTable Scrolling */
        .datatable-scroll-wrap {
            overflow-x: auto !important;
            overflow-y: auto !important;
            max-height: 70vh;
            position: relative;
        }

        .datatable-scroll-wrap table {
            margin-bottom: 0;
            min-width: 100%;
            white-space: nowrap;
        }

        .datatable-scroll-wrap thead th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 10;
            border-bottom: 2px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .datatable-scroll-wrap tbody td {
            white-space: nowrap;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .datatable-scroll-wrap tbody td:hover {
            white-space: normal;
            word-wrap: break-word;
            overflow: visible;
            background: rgba(0,123,255,0.1);
            z-index: 5;
            position: relative;
        }

        /* Custom scrollbar styling */
        .datatable-scroll-wrap::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .datatable-scroll-wrap::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .datatable-scroll-wrap::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .datatable-scroll-wrap::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .datatable-scroll-wrap {
                max-height: 60vh;
            }

            .datatable-scroll-wrap tbody td {
                max-width: 150px;
            }
        }

        /* Fixed column styling for better UX */
        .DTFC_LeftBodyWrapper table,
        .DTFC_RightBodyWrapper table {
            margin-bottom: 0;
        }

        .DTFC_LeftBodyWrapper,
        .DTFC_RightBodyWrapper {
            background: white;
        }

        /* System Watermark */
        .system-watermark {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }

        .watermark-text {
            font-size: 72px;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.05);
            transform: rotate(-45deg);
            letter-spacing: 2px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        /* Dark mode watermark */
        .dark .watermark-text {
            color: rgba(255, 255, 255, 0.08);
        }

        /* Responsive watermark adjustments */
        @media (max-width: 768px) {
            .watermark-text {
                font-size: 48px;
            }
        }

        @media (max-width: 480px) {
            .watermark-text {
                font-size: 36px;
            }
        }
    </style>
</head>

<body style="overflow: visible;" class="dark:bg-gray-900 dark:text-gray-100">
    <!-- System Watermark -->
    <div class="system-watermark">
        <div class="watermark-text">{{ $systemConfig ? $systemConfig->watermark_title : 'HMS CAMBODIA' }}</div>
    </div>
    @if (config('init.loading.enabled') === true)
        <div class="card-overlay d-none" id="body-overlay"><span class="{{ config('init.loading.icon') }}"></span></div>
    @endif
    @include('layouts.partials.navigation')
    <div class="page-content">
        @include('layouts.partials.sidebar')
        <div class="content-wrapper">
            <div class="content-inner">
                @include('layouts.partials.breadcrumb')
                <div class="content">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-remote" tabindex="-1" role="dialog" aria-labelledby="modal-remote-label" aria-hidden="true"></div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('layouts.partials.script')
    @include('layouts.partials.scripts.image-editor')
    @stack('scripts')
</body>

</html>
