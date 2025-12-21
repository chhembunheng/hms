 <!-- Main navbar -->
@php
    $systemConfig = \App\Models\Settings\SystemConfiguration::first();
@endphp
 <div class="navbar navbar-static shadow-none" style="min-height: 50px;">
     <div class="container-fluid p-0">
         <div class="d-flex align-items-center">
             <div class="d-flex me-2 align-items-center">
                 <button type="button" class="navbar-toggler sidebar-main-toggle sidebar-mobile-main-toggle rounded-pill">
                     <i class="fa-solid fa-bars fa-fw" style="font-size: 1.1rem;"></i>
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
                     <i class="fa-solid fa-chevron-down fa-fw" style="font-size: 0.75rem;"></i>
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
                             <img src="{{ asset('assets/images/default/male-avatar.jpg') }}"
                                 class="w-32px h-32px rounded-pill">
                             <span class="status-indicator bg-success"></span>
                         </div>
                         <span class="d-none d-lg-inline-block mx-lg-2">{{ auth()->user()->name }}</span>
                         <i class="fa-solid fa-chevron-down fa-fw" style="font-size: 0.75rem;"></i>
                     </a>

                     <div class="dropdown-menu dropdown-menu-end">
                         @if (Route::has('settings.account'))
                             <a href="{{ route('settings.account') }}" class="dropdown-item">
                                 <i class="fa-solid fa-user-circle me-2"></i>
                                 {{ __('root.nav.manage_your_account') }}
                             </a>
                         @endif
                         <a href="#" class="dropdown-item" onclick="clearCache()">
                             <i class="fa-solid fa-broom-wide me-2 fa-fw"></i>
                             {{ __('global.clear_cache') }}
                         </a>
                         <a href="#" class="dropdown-item" onclick="logout()">
                             <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                             {{ __('root.nav.logout') }}
                         </a>
                     </div>
                 </li>
             @endif
         </ul>
     </div>
 </div>
 @include('layouts.partials.notifications')
