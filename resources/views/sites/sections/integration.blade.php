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
                            <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 768px) 95vw, {{ $img['width'] }}px" alt="Image Slider {{ $title }}" loading="lazy" width="{{ $img['width'] }}" height="auto">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="projects-details-desc">
                <div class="features-text">
                    <div class="row">
                        @foreach ($integration->integrations ?? [] as $platform)
                            <div class="col-lg-3 col-md-6">
                                <section class="widget widget_recent_entries mb-4">
                                    <h3 class="widget-title d-flex align-items-baseline gap-2">
                                        <img src="{{ webpasset($platform->image) }}" style="height: 28px; object-fit: cover;" loading="lazy" alt="{{ $platform->name }}">
                                        <span>{{ $platform->name }} &nbsp;<i class="fa fa-link text-success" style="font-size: 16px;"></i></span>
                                    </h3>
                                    <ul>
                                        @foreach ($platform->features ?? [] as $feature)
                                            <li class="mb-2"><i class="fa fa-check-circle" aria-hidden="true"></i> &nbsp;{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                </section>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="project-details-info">
                    <div class="single-info-box">
                        <h4>Author</h4>
                        <span>Pamela Lawrence</span>
                    </div>
                    <div class="single-info-box">
                        <h4>Category</h4>
                        <span>Virtual, Technology</span>
                    </div>
                    <div class="single-info-box">
                        <h4>Date</h4>
                        <span>June 20, 2022</span>
                    </div>
                    <div class="single-info-box">
                        <h4>Share</h4>
                        <ul class="social">
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" class="share-fb" aria-label="Share on Facebook"> <i class="fab fa-facebook-f"></i></a>
                            </li>
                            <li>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" class="share-x" aria-label="Share on X"> <i class="fab fa-x"></i></a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" class="share-li" aria-label="Share on LinkedIn"> <i class="fab fa-linkedin-in"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="single-info-box">
                        <h4>Works Preview</h4>
                        <a href="#" class="default-btn">Live Preview</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
