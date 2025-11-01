@props(['integrations' => [], 'title' => '', 'subtitle' => ''])
@if ($integrations)
    <section class="services-section bg-grey section-padding">
        <div class="container">
            <div class="row">
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
                @foreach ($integrations as $integration)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-services-item">
                            <div class="services-icon">
                                <i class="{{ $integration->icon }}"></i>
                            </div>
                            <h3>{{ $integration->name }}</h3>
                            <div class="services-btn-link">
                                <a href="{{ Route::has('frontend.integrations') ? route('frontend.integrations', ['slug' => $integration->slug, 'locale' => app()->getLocale()]) : '#' }}" class="services-link">{{ __('global.read_more') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
