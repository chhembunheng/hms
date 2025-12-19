<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-locale="{{ app()->getLocale() }}" dir="ltr" translate="no">

<head>
    <meta charset="utf-8">
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-translate-customization" content="..."/>
    <meta name="robots" content="noarchive">
    <title>{{ config('app.name') }}</title>
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
    </style>
</head>

<body style="overflow: visible;" class="dark:bg-gray-900 dark:text-gray-100">
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
