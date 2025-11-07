<section class="footer-subscribe-wrapper">
    <div class="footer-area ptb-100" style="background-image: url('{{ webpasset('site/assets/img/footer-bg.png') }}');">
        <div class="container">
            <div class="row">
                {{-- Company / About --}}
                <div class="col-lg-4 col-md-6">
                    <div class="single-footer-widget">
                        @php
                            $img = webp_variants('assets/logo/full-blue.png', 'bxs', null, 60);
                        @endphp
                        <a class="footer-logo" href="{{ Route::currentRouteName() == 'frontend.home' ? '#' : (Route::has('home') ? route('home', ['locale' => app()->getLocale()]) : '/') }}" aria-label="{{ __('global.home') }}">
                            <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 600px) 95vw, {{ $img['width'] }}px" alt="Brand Name Logo" loading="lazy" width="{{ $img['width'] }}" height="auto">
                        </a>
                        <p>
                            <strong>{{ __('global.company_name') }}</strong>
                            {{ __('global.footer_about_short') }}
                        </p>
                        <ul class="footer-social">
                            <li>
                                <a href="https://www.facebook.com/Wintechsoftwaredevelopment" aria-label="Facebook">
                                    <i class="fa-brands fa-facebook fa-fw" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://t.me/+85512345855" aria-label="Telegram">
                                    <i class="fa-brands fa-telegram fa-fw" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- Services --}}
                <div class="col-lg-2 col-md-6">
                    <div class="single-footer-widget">
                        <div class="footer-heading">
                            <h3>{{ __('global.our_services') }}</h3>
                        </div>
                        <ul class="footer-quick-links">
                            @foreach (json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/services.json'))) ?? [] as $service)
                                <li>
                                    <a href="{{ Route::has('services') ? route('services', ['locale' => app()->getLocale(), 'slug' => $service->slug]) : '#' }}" aria-label="{{ $service->name }}">
                                        {{ $service->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Useful Links --}}
                <div class="col-lg-2 col-md-6">
                    <div class="single-footer-widget">
                        <div class="footer-heading">
                            <h3>{{ __('global.useful_links') }}</h3>
                        </div>

                        <ul class="footer-quick-links">
                            @if (Route::has('about-us'))
                                <li>
                                    <a href="{{ route('about-us', ['locale' => app()->getLocale()]) }}">
                                        {{ __('global.about_us') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('services'))
                                <li>
                                    <a href="{{ route('services', ['locale' => app()->getLocale()]) }}">
                                        {{ __('global.services') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('products'))
                                <li>
                                    <a href="{{ route('products', ['locale' => app()->getLocale()]) }}">
                                        {{ __('global.products') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('projects'))
                                <li>
                                    <a href="{{ route('projects', ['locale' => app()->getLocale()]) }}">
                                        {{ __('global.case_study') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('contact-us'))
                                <li>
                                    <a href="{{ route('contact-us', ['locale' => app()->getLocale()]) }}">
                                        {{ __('global.contact_us') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('privacy-policy'))
                                <li>
                                    <a href="{{ route('privacy-policy', ['locale' => app()->getLocale()]) }}">
                                        {{ __('global.privacy_policy') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('terms-condition'))
                                <li>
                                    <a href="{{ route('terms-condition', ['locale' => app()->getLocale()]) }}">
                                        {{ __('global.terms_and_conditions') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('faq'))
                                <li>
                                    <a href="{{ route('faq', ['locale' => app()->getLocale()]) }}">
                                        {{ __('global.frequently_asked_questions') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-footer-widget">
                        <div class="footer-heading">
                            <h3>{{ __('global.contact_info') }}</h3>
                        </div>
                        <div class="footer-info-contact">
                            <i class="fa fa-mobile-screen-button"></i>
                            <h3>{{ __('global.phone') }}</h3>
                            <span>
                                <a href="tel:+85512345855" class="text-primary">+855 12 345 855</a>
                            </span>
                        </div>

                        <div class="footer-info-contact">
                            <i class="fa fa-envelope"></i>
                            <h3>{{ __('global.email') }}</h3>
                            <span>
                                <a href="mailto:info@wintech.com.kh" class="text-primary">info@wintech.com.kh</a>
                            </span>
                        </div>

                        <div class="footer-info-contact">
                            <i class="fa fa-home"></i>
                            <h3>{{ __('global.address') }}</h3>
                            <span class="text-primary">
                                {{ __('global.company_address_full') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- row -->
        </div><!-- container -->
    </div><!-- footer-area -->
</section>

<div class="copyright-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <p>
                    <i class="far fa-copyright"></i>
                    {{ date('Y') }} {{ __('global.company_name') }} - {{ __('global.all_rights_reserved') }}
                </p>
            </div>
            <div class="col-lg-6 col-md-6">
                <ul>
                    <li>
                        <a href="{{ route('terms-condition', ['locale' => app()->getLocale()]) }}">
                            {{ __('global.terms_and_conditions') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy-policy', ['locale' => app()->getLocale()]) }}">
                            {{ __('global.privacy_policy') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div><!-- row -->
    </div><!-- container -->
</div>
