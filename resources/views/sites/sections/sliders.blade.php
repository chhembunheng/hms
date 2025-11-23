<header class="slider slider-prlx">
    <div class="swiper-container parallax-slider">
        <div class="swiper-wrapper">
            @foreach ($sliders as $i => $slider)
                @php
                    $img = webp_variants($slider->slider_image, 'banner', null, 80); // expect width & height keys
                    $isFirst = $i === 0;
                @endphp
                <div class="swiper-slide">
                    <div class="slide-figure">
                        <picture>
                            <source type="image/webp" srcset="{{ $img['srcset'] }}">
                            <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="100vw"
                                alt="{{ $slider->title ?? 'Slider image' }}"
                                {{ $isFirst ? 'fetchpriority=high decoding=async' : 'loading=lazy decoding=async' }}
                                width="{{ $img['width'] }}" height="{{ $img['height'] ?? '' }}"
                                class="slide-img">
                        </picture>
                        <span class="slide-overlay" aria-hidden="true"></span>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2 col-md-12">
                                <div class="caption">
                                    <h1>{{ $slider->getSliderTitle() }}</h1>
                                    <p>{{ $slider->getSliderDescription() }}</p>
                                    <div class="banner-btn home-slider-btn">
                                        @if (Route::has($slider->getRoute()))
                                            <a href="{{ route($slider->getRoute(), ['locale' => app()->getLocale(), 'slug' => $slider->slug]) }}"
                                                class="default-btn-one"
                                                aria-label="{{ $slider->getSliderTitle() }}">
                                                {{ $slider->buttonText() }} <span></span>
                                            </a>
                                        @endif
                                        <a class="default-btn"
                                            href="{{ Route::has('contact') ? route('contact', ['locale' => app()->getLocale()]) : '#' }}">
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
        <!-- slider setting -->
        <div class="control-text">
            <div class="swiper-button-prev swiper-nav-ctrl prev-ctrl cursor-pointer">
                <span class="arrow prv"></span>
            </div>
            <div class="swiper-button-next swiper-nav-ctrl next-ctrl cursor-pointer">
                <span class="arrow nxt"></span>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</header>
