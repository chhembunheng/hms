@props([
    'articles' => [],
    'viewmore' => false,
    'paginations' => false,
    'title' => '',
    'subtitle' => '',
])
@if ($articles)
    <section class="blog-section bg-grey pt-70 pb-70">
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
                @foreach ($articles as $article)
                    <div class="col-lg-4 col-md-6">
                        <article class="blog-item">
                            <div class="blog-image">
                                <a href="{{ Route::has('frontend.blogs') ? route('frontend.blogs', ['locale' => app()->getLocale(), 'slug' => $article->slug]) : '#' }}" aria-label="Read article {{ $article->title }}">
                                    @php
                                        $img = webp_variants($article->image, 'product', null, 80);
                                    @endphp
                                    <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 768px) 95vw, {{ $img['width'] }}px" alt="{{ $article->title }}" loading="lazy" width="{{ $img['width'] }}" height="auto">
                                </a>
                            </div>
                            <div class="single-blog-item">
                                <ul class="blog-list d-flex justify-content-between align-items-center">
                                    <li>
                                        <a href="#"> <i class="fa-duotone fa-solid fa-user-alt fa-fw" aria-hidden="true"></i> &nbsp;{{ $article->author ?? 'N/A' }}</a>
                                    </li>
                                    <li>
                                        <a href="#"> <i class="fa-duotone fa-solid fa-calendar-clock fa-fw" aria-hidden="true"></i> &nbsp;{{ $article->date ? date('d M Y', strtotime($article->date)) : 'N/A' }}</a>
                                    </li>
                                </ul>
                                <div class="blog-content">
                                    <h3>
                                        <a href="{{ Route::has('frontend.blogs') ? route('frontend.blogs', ['locale' => app()->getLocale(), 'slug' => $article->slug]) : '#' }}" aria-label="Read article {{ $article->title }}">
                                            {{ $article->title ?? 'N/A' }}
                                        </a>
                                    </h3>
                                    <p>{{ $article->excerpt ?? 'N/A' }}</p>
                                    <div class="blog-btn"> <a href="{{ Route::has('frontend.blogs') ? route('frontend.blogs', ['locale' => app()->getLocale(), 'slug' => $article->slug]) : '#' }}" aria-label="Read article {{ $article->title }}"
                                            class="blog-btn-one">{{ __('global.read_more') }} &nbsp;<i class="fa-solid fa-arrow-right-long fa-fw" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
                @if ($viewmore)
                    <div class="col-lg-12 col-md-12">
                        <div class="blog-more-btn">
                            <a class="default-btn" href="#">More Articles&nbsp;&nbsp;<i class="fa-solid fa-arrow-right-long fa-fw" aria-hidden="true"></i></a>
                        </div>
                    </div>
                @endif
                @if ($paginations)
                    <div class="col-lg-12 col-md-12">
                        <div class="pagination-area">
                            <a href="blog-1.html#" class="prev page-numbers"><i class="fa-sharp-duotone fa-solid fa-angles-left" aria-hidden="true"></i></a>
                            <a href="blog-1.html#" class="page-numbers">1</a>
                            <span class="page-numbers current" aria-current="page">2</span>
                            <a href="blog-1.html#" class="page-numbers">3</a>
                            <a href="blog-1.html#" class="page-numbers">4</a>
                            <a href="blog-1.html#" class="next page-numbers"><i class="fa-sharp-duotone fa-solid fa-angles-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
