<header class="slider slider-prlx">
    <div class="swiper-container parallax-slider">
        <div class="swiper-wrapper">
            @foreach ($sliders as $i => $slider)
                @php
                    $img = webp_variants($slider->image, 'banner', null, 80); // expect width & height keys
                    $isFirst = $i === 0;
                @endphp

                <div class="swiper-slide">
                    <div class="slide-figure">
                        <picture>
                            <source type="image/webp" srcset="{{ $img['srcset'] }}">
                            <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="100vw" alt="{{ $slider->title ?? 'Slider image' }}" {{ $isFirst ? 'fetchpriority=high decoding=async' : 'loading=lazy decoding=async' }} width="{{ $img['width'] }}"
                                height="{{ $img['height'] ?? '' }}" {{-- use a numeric height if available --}} class="slide-img">
                        </picture>
                        <span class="slide-overlay" aria-hidden="true"></span>
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2 col-md-12">
                                <div class="caption">
                                    <h1>{{ $slider->title }}</h1>
                                    <p>{{ $slider->description }}</p>
                                    <div class="banner-btn home-slider-btn">
                                        @if (Route::has($slider->button_route))
                                            <a href="{{ route($slider->button_route, ['locale' => app()->getLocale()]) }}" class="default-btn-one" aria-label="Learn more about {{ $slider->title }}">
                                                {{ $slider->button_text }} <span></span>
                                            </a>
                                        @endif
                                        <a class="default-btn" href="{{ Route::has('frontend.contact') ? route('contact', ['locale' => app()->getLocale()]) : '#' }}">
                                            {{ __('global.contact_us') }} <span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="swiper-pagination"></div>
    </div>
</header>
