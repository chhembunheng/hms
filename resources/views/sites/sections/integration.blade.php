@props(['integration' => null, 'integrations' => [], 'title' => '', 'subtitle' => ''])
<section class="project-details-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="image-sliders owl-carousel owl-theme">
                    @foreach ($integration->banners ?? [] as $image)
                        <div class="project-details-image">
                            @php
                                $img = webp_variants($image, 'banner', null, 80);
                            @endphp
                            <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}"
                                sizes="(max-width: 768px) 95vw, {{ $img['width'] }}px"
                                alt="Image Slider {{ $title }}" loading="lazy" width="{{ $img['width'] }}"
                                height="auto">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="projects-details-desc">
                <div class="features-text">
                    <div class="row">
                        @foreach ($integration->children ?? [] as $platform)
                            <div class="col-lg-3 col-md-6">
                                <section class="widget widget_recent_entries mb-4">
                                    <h3 class="widget-title d-flex align-items-baseline gap-2">
                                        <img src="{{ webpasset($platform->image) }}"
                                            style="height: 28px; object-fit: cover;" loading="lazy"
                                            alt="{{ $platform->getName() }}">
                                        <span>{{ $platform->getName() }} &nbsp;<i class="fa fa-link text-success"
                                                style="font-size: 16px;"></i></span>
                                    </h3>
                                    <ul>
                                        @foreach ($platform->children ?? [] as $feature)
                                            <li class="mb-2"><i class="fa fa-check-circle" aria-hidden="true"></i>
                                                &nbsp;{{ $feature->getName() }}</li>
                                        @endforeach
                                    </ul>
                                </section>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="project-details-info">
                    <div class="single-info-box">
                        <h4>{{ __('global.author') }}</h4>
                        <span>{{ __('global.wintech_team') }}</span>
                    </div>
                    <div class="single-info-box">
                        <h4>{{ __('global.category') }}</h4>
                        <span>Virtual, Technology</span>
                    </div>
                    <div class="single-info-box">
                        <h4>{{ __('global.date') }}</h4>
                        <span>{{ $integration->created_at?->format('M d, Y') }}</span>
                    </div>
                    <div class="single-info-box">
                        <x-share/>
                    </div>
                    <div class="single-info-box">
                        <h4>{{ __('global.live_preview') }}</h4>
                        <a href="#" class="default-btn">
                            {{ __('global.view_now') }}
                            <span></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
