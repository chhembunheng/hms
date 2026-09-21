 <!-- Main navbar -->
@php
    $systemConfig = \App\Models\Settings\SystemConfiguration::first();
@endphp
 <div class="navbar navbar-static shadow-none" style="min-height: 50px;">
     <div class="container-fluid p-0">
         <div class="d-flex align-items-center">
             <div class="d-flex me-2 align-items-center">
                 <button type="button" class="navbar-toggler sidebar-toggle-btn rounded-pill" id="sidebar-toggle-btn" aria-label="Toggle Sidebar">
                     <i data-lucide="menu" style="width: 18px; height: 18px;"></i>
                 </button>
             </div>
             <div class="navbar-brand flex-1 h-32px">
                 <a href="{{ route('dashboard.index') }}" class="d-inline-flex align-items-center">
                     @if($systemConfig && $systemConfig->logo_path)
                         <img src="{{ Storage::url($systemConfig->logo_path) }}" alt="Logo" class="me-2" style="height: 28px; width: auto;">
                     @endif
                     <span class="fw-bold fs-3 text-white">{{ $systemConfig ? $systemConfig->localized_hotel_name : config('app.name') }}</span>
                 </a>
             </div>
         </div>

        <ul class="nav flex-row justify-content-end align-items-center">
             <li class="nav-item nav-item-dropdown-lg dropdown language-switch">
                 @php
                     $languages = collect(config('init.languages'));
                     $currentLocale = app()->getLocale();
                     $currentLanguage = $languages->firstWhere('code', $currentLocale);
                 @endphp
                 <a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill lang-flag-text"
                     data-bs-toggle="dropdown" aria-expanded="false">
                     <img src="{{ asset($currentLanguage['flag']) }}" class="lang-flag">
                     <span class="d-none d-lg-inline-block ms-2 me-1">{{ $currentLanguage['name'] }}</span>
                     <i data-lucide="chevron-down" style="width: 14px; height: 14px;"></i>
                 </a>
                 <div class="dropdown-menu dropdown-menu-end">
                     @foreach ($languages as $locale => $lang)
                         @if ($locale != app()->getLocale())
                             <a href="{{ Route::has('admin.lang') ? route('admin.lang', ['lang' => $locale]) : '#' }}"
                                 class="dropdown-item lang-flag-text">
                                 <img src="{{ asset($lang['flag']) }}" class="lang-flag">
                                 <span class="ms-2">{{ $lang['name'] }}</span>
                             </a>
                         @endif
                     @endforeach
                 </div>
             </li>
             @if (auth()->check())
                 <li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
                     <a href="index.html#" class="navbar-nav-link align-items-center rounded-pill p-1"
                         data-bs-toggle="dropdown">
                         <div class="status-indicator-container">
                                @if (auth()->user()->avatar)
                                    <img src="{{ asset(auth()->user()->avatar) }}"
                                        class="w-32px h-32px rounded-pill">
                                @else
                             <img src="{{ asset('assets/images/default/male-avatar.jpg') }}"
                                 class="w-32px h-32px rounded-pill">
                                @endif
                             <span class="status-indicator bg-success"></span>
                         </div>
                         <span class="d-none d-lg-inline-block mx-lg-2">{{ auth()->user()->name }}</span>
                         <i data-lucide="chevron-down" style="width: 14px; height: 14px;"></i>
                     </a>

                     <div class="dropdown-menu dropdown-menu-end">
                         @if (Route::has('settings.account'))
                             <a href="{{ route('settings.account') }}" class="dropdown-item">
                                 <i data-lucide="user" class="me-2" style="width: 16px; height: 16px;"></i>
                                 {{ __('root.nav.manage_your_account') }}
                             </a>
                         @endif
                         <a href="#" class="dropdown-item" onclick="clearCache()">
                             <i data-lucide="sparkles" class="me-2" style="width: 16px; height: 16px;"></i>
                             {{ __('global.clear_cache') }}
                         </a>
                         <a href="#" class="dropdown-item" onclick="logout()">
                             <i data-lucide="log-out" class="me-2" style="width: 16px; height: 16px;"></i>
                             {{ __('root.nav.logout') }}
                         </a>
                     </div>
                 </li>
             @endif
         </ul>
     </div>
 </div>
 @include('layouts.partials.notifications')
