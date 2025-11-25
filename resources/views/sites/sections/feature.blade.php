@props(['feature' => null, 'left' => false, 'title' => '', 'subtitle' => ''])
@if ($feature->details ?? null)
    <section class="about-area">
        <div class="container">
            <h1 class="my-4">{{ __('global.features') }}</h1>
            <div class="row d-flex align-items-center">
                @if ($left && isset($feature->image))
                    <div class="col-lg-5 offset-lg-1 col-md-12 col-sm-12">
                        <div class="about-image">
                            <img src="{{ webpasset($feature->image) }}" alt="{{ $title }}">
                        </div>
                    </div>
                @endif
                @if ($left && $feature->icon)
                    <div class="col-lg-5 offset-lg-1 col-md-12 col-sm-12">
                        <div class="about-image text-center">
                            <div class="icon text-center">
                                <i class="{{ $feature->icon }}"
                                    style="font-size: 200px; color: {{ sprintf('#%06X', mt_rand(0, 0xffffff)) }}"></i>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="about-content">
                        <div class="about-content-text">
                            <h4>{{ $title }}</h4>
                            {!! $subtitle !!}
                            @foreach ($feature->details as $detail)
                                <h6 class="mb-3">
                                    <i class="fa-solid fa-check text-success fa-fw"></i>
                                    {!! $detail->getName() ?? '' !!}
                                </h6>
                                <p class="ml-4">{{ $detail->getDescription() ?? '' }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if (!$left && $feature->icon)
                    <div class="col-lg-5 offset-lg-1 col-md-12 col-sm-12">
                        <div class="about-image text-center">
                            <div class="icon text-center">
                                <i class="{{ $feature->icon }}"
                                    style="font-size: 200px; color: {{ sprintf('#%06X', mt_rand(0, 0xffffff)) }}"></i>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!$left && isset($feature->image))
                    <div class="col-lg-5 offset-lg-1 col-md-12 col-sm-12">
                        <div class="about-image">
                            <img src="{{ webpasset($feature->image) }}" alt="{{ $title }}">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
