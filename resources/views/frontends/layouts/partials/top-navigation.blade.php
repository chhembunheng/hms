 <header id="header" role="banner" class="header-modern sticky-top bg-white shadow-sm">
     <nav class="navbar navbar-expand-lg navbar-light d-flex align-items-center justify-content-between">
         <!-- Logo -->
         <a class="navbar-brand me-4" style="flex: 1" href="{{ Route::currentRouteName() == 'welcome' ? '#' : route('welcome', ['locale' => app()->getLocale()]) }}" title="Home">
             <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 600px) 95vw, {{ $img['width'] }}px" alt="Brand Name Logo" loading="lazy" width="{{ $img['width'] }}" height="auto" class="img-fluid" style="max-height: 48px;">
         </a>
         <!-- Navigation Menu -->
         <div class="collapse navbar-collapse" id="navbarContent">
             <ul class="navbar-nav">
                 @foreach ($navigations ?? [] as $navigation)
                     @include('frontends.layouts.partials.item-navigation', ['navigation' => $navigation])
                 @endforeach
             </ul>
         </div>
         <!-- Right Side Items (Language Selector) -->
         <div class="d-flex align-items-center me-2 language-selector-wrapper">
             <div class="language-selector dropdown">
                 @php
                     $languages = collect(config('init.languages'));
                     $currentLocale = app()->getLocale();
                     $currentLanguage = $languages->firstWhere('code', $currentLocale);
                 @endphp
                 <button class="btn btn-link nav-link p-0 d-flex align-items-center text-decoration-none" id="languageToggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Language selector">
                     <img src="{{ asset($currentLanguage['flag']) }}" class="language-flag" alt="Language Flag" style="width: 20px; height: 14px; object-fit: cover;">
                     <span class="d-none d-md-inline small fw-500">{{ $currentLanguage['name'] }}</span>
                     <i class="fa-solid fa-chevron-down fa-fw ms-1" style="font-size: 0.75rem;"></i>
                 </button>
                 <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageToggle">
                     @foreach (config('init.languages') as $language)
                         @php
                             $switchUrl = route(Route::currentRouteName(), array_merge(request()->route()->parameters(), ['locale' => $language['code']]));
                         @endphp
                         <li>
                             <a href="{{ $switchUrl }}" class="dropdown-item d-flex align-items-center" aria-label="{{ __('global.switch_language_to', ['lang' => $language['name']]) }}">
                                 <img src="{{ asset($language['flag']) }}" class="language-flag" alt="{{ $language['name'] }}" style="width: 20px; height: 14px; object-fit: cover; flex-shrink: 0;">
                                 <span class="flex-grow-1">{{ $language['name'] }}</span>
                             </a>
                         </li>
                     @endforeach
                 </ul>
             </div>
         </div>
         <!-- Mobile Toggle Button -->
         <button class="navbar-toggler border-0 p-0 ms-auto order-lg-last" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
         </button>
     </nav>
 </header>
