@props(['service' => null, 'services' => [], 'title' => '', 'subtitle' => ''])
<section class="services-details-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="services-details-content">
                    @isset($service->image)
                        <div class="services-details-image">
                            @php
                                $img = webp_variants($service->image, 'banner', null, 80);
                            @endphp
                            <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 768px) 95vw, 1200px" alt="{{ $title }}" loading="lazy" width="1200" height="auto">
                        </div>
                    @endisset
                    <div class="features-text">
                        {!! $service->content ?? '' !!}
                        @isset($service->keys)
                            <ul class="service-features-list">
                                @foreach ($service->keys as $key)
                                    <li><i class="fa fa-check"></i>{{ $key }}</li>
                                @endforeach
                            </ul>
                        @endisset
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <aside class="services-widget">
                    @isset($services)
                        <section class="widget widget_categories">
                            <h3 class="widget-title">{{ __('global.our_services') }}</h3>
                            <ul>
                                @foreach ($services as $srv)
                                    <li class="{{ $srv->id == $service->id ? 'active' : '' }}">
                                        <a href="{{ route('services', ['slug' => $srv->slug, 'locale' => app()->getLocale()]) }}" aria-label="{{ $srv->name }}">{{ $srv->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endisset
                    <section class="widget widget_download_btn">
                        <h3 class="widget-title">{{ __('global.company_profile') }}</h3>
                        <div class="section-bottom">
                            <a href="{{ asset('site/assets/files/wintech-company-profile-latest-2025-10-23.pdf') }}" target="_blank" class="default-btn" aria-label="{{ __('global.download_company_profile') }}">{{ __('global.download_pdf') }} <span></span></a>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</section>
