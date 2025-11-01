<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name') }}</title>
    @include('layouts.partials.style')
    @stack('css')
</head>

<body style="overflow: visible;">
    {{-- <div class="card-overlay" id="body-overlay"><span class="ph ph-spinner-gap spinner"></span></div> --}}
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
    <div class="modal fade" id="editorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Editor</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="editorWrap">
                        <div id="tuiEditor"></div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            #editorModal .modal-fullscreen .modal-content,
            #editorModal .modal-fullscreen .modal-body {
                height: 100%;
            }

            #editorModal .modal-body {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            #editorWrap {
                flex: 1;
                min-height: 0;
                border: 1px solid #e5e7eb;
            }

            #tuiEditor {
                width: 100%;
                height: 100%;
                position: relative;
            }
            #tuiEditor .tui-image-editor-help-menu {
                display: flex;
            }
            #tuiEditor .tie-crop-button.action {
                display: flex;
                align-items: center;
                gap: 5px;
                justify-content: center;
            }
            #tuiEditor .tie-crop-button.action .tui-image-editor-button {
                display: flex;
                align-items: center;
                gap: 5px;
            }
            #tuiEditor .tui-image-editor-header {
                visibility: hidden;
            }
        </style>
    </div>
    <div class="modal fade" id="modal-remote" tabindex="-1" role="dialog" aria-labelledby="modal-remote-label" aria-hidden="true"></div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('layouts.partials.script')
    @stack('scripts')
</body>

</html>
