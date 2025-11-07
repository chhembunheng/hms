@props(['services' => [], 'title' => '', 'subtitle' => ''])
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
            @foreach ($services as $service)
                <div class="col-lg-4 col-md-6">
                    <article class="single-services-item">
                        <div class="services-icon">
                            <i class="{{ $service->icon }}" aria-hidden="true"></i>
                        </div>
                        <h3>{{ $service->name }}</h3>
                        <p>{!! $service->short_description ?? '' !!}</p>
                        <div class="services-btn-link">
                            <a href="{{ Route::has('services') ? route('services', ['locale' => app()->getLocale(), 'slug' => $service->slug]) : '#' }}" class="services-link">
                                {{ __('global.read_more') }}
                                <span class="visually-hidden">{{ $service->name }}</span>
                            </a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
