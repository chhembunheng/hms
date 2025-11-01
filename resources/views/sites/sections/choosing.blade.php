@props([
    'choosing' => [],
    'title' => __('global.title'),
    'subtitle' => __('global.subtitle'),
])

@if ($choosing)
    <section class="overview-section bg-grey section-padding">
        <div class="container">
            <div class="row align-items-center">
                @if ($title || $subtitle)
                    <div class="col-md-12">
                        <div class="section-title">
                            @if ($title)
                                <h2>{{ $title }}</h2>
                            @endif
                            @if ($subtitle)
                                <p>{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="col-lg-6">
                    <div class="overview-image">
                        @php
                            $img = webp_variants('site/assets/img/choose.jpg', 'portrait', null, 80);
                        @endphp
                        <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 768px) 95vw, {{ $img['width'] }}px" alt="Why Choose Us Image Banner" loading="lazy" width="{{ $img['width'] }}" height="{{ intval($img['width'] * 2 / 3) }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="overview-content">
                        <h2>{{ __('global.heading') }}</h2>
                        <p>{{ __('global.choose_description') }}</p>
                        <ul class="features-list">
                            @foreach ($choosing as $choose)
                                <li>
                                    <span>
                                        <i class="{{ $choose->icon }} fa-fw"></i>
                                        &nbsp;{{ $choose->name }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
