@props(['details' => [], 'box' => null, 'left' => false, 'title' => '', 'subtitle' => ''])
@if ($details)
    <section class="about-area section-padding">
        <div class="container">
            <div class="row d-flex align-items-center">
                @if ($left && isset($box->image))
                    <div class="col-lg-5 offset-lg-1 col-md-12 col-sm-12">
                        <div class="about-image">
                            <img src="{{ webpasset($box->image) }}" alt="About image">
                        </div>
                    </div>
                @endif
                @if ($left && $box->icon)
                    <div class="col-lg-5 offset-lg-1 col-md-12 col-sm-12">
                        <div class="about-image text-center">
                            <div class="icon text-center">
                                <i class="{{ $box->icon }}" style="font-size: 200px; color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}"></i>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="about-content">
                        <div class="about-content-text">
                            <h2>{{ $title }}</h2>
                            <p>{{ $subtitle }}</p>
                            @foreach ($details as $detail)
                                <h6 class="mb-3">
                                    <i class="fa-solid fa-check text-success fa-fw"></i>
                                    {{ $detail->title ?? '' }}
                                </h6>
                                <p class="ml-4">{{ $detail->description ?? '' }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if (!$left && $box->icon)
                    <div class="col-lg-5 offset-lg-1 col-md-12 col-sm-12">
                        <div class="about-image text-center">
                            <div class="icon text-center">
                                <i class="{{ $box->icon }}" style="font-size: 200px; color: {{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}"></i>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!$left && isset($box->image))
                    <div class="col-lg-5 offset-lg-1 col-md-12 col-sm-12">
                        <div class="about-image">
                            <img src="{{ webpasset($box->image) }}" alt="About image">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
