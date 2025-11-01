@props([
    'articles' => [],
    'article' => null,
    'viewmore' => false,
    'paginations' => false,
    'title' => '',
    'subtitle' => '',
])
<section class="blog-details-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="blog-details-desc">
                    <div class="article-image">
                        @php
                            $img = webp_variants($article->image_cover, 'banner', null, 80);
                        @endphp
                        <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 768px) 95vw, {{ $img['width'] }}px" alt="{{ $article->title }}" loading="lazy" width="{{ $img['width'] }}" height="auto">
                    </div>
                    <div class="article-content">
                        <div class="entry-meta">
                            <ul>
                                <li> <span>{{ __('global.posted_on') }}:</span> <a href="#">{{ date('F j, Y', strtotime($article->date)) }}</a></li>
                                <li> <span>{{ __('global.posted_by') }}:</span> <a href="#">{{ $article->author }}</a></li>
                            </ul>
                        </div>
                        {!! $article->content ?? '' !!}
                        <ul class="wp-block-gallery columns-2">
                            @foreach ($article->gallery ?? [] as $image)
                                <li class="blocks-gallery-item">
                                    <figure>
                                        @php
                                            $img = webp_variants($image, 'product', null, 80);
                                        @endphp
                                        <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 768px) 95vw, {{ $img['width'] }}px" alt="{{ $article->title }}" loading="lazy" width="{{ $img['width'] }}" height="auto">
                                    </figure>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="article-footer">
                        <div class="article-tags"> <span>{{ __('global.tag') }}:</span>
                            {!! implode(', ', array_map(fn($tag) => '<a href="#">' . ucfirst($tag) . '</a>', $article->tags ?? [])) !!}
                        </div>
                        <div class="article-share">
                            <ul class="social">
                                <li><span>{{ __('global.share') }}:</span></li>
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
                    </div>
                    <div class="post-navigation">
                        <div class="navigation-links">
                            <div class="nav-previous">
                                @if (!empty($prev))
                                    <a href="{{ $prev }}"> <i class="fa fa-arrow-left"></i> {{ __('global.previous_post') }}</a>
                                @endif
                            </div>
                            <div class="nav-next">
                                @if (!empty($next))
                                    <a href="{{ $next }}">{{ __('global.next_post') }} <i class="fa fa-arrow-right"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <aside class="widget-area" id="secondary">
                    <section class="widget widget_techone_posts_thumb">
                        <h3 class="widget-title">{{ __('global.popular_posts') }}</h3>
                        @foreach ($articles as $post)
                            @if ($loop->index > 2)
                                @continue
                            @endif
                            <article class="item">
                                <a href="{{ route('frontend.blogs', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}" class="thumb"> <span class="fullimage cover" role="img" style="background-image: url('{{ webpasset($post->image) }}');"></span></a>
                                <div class="info">
                                    <span>{{ $post->date }}</span>
                                    <h4 class="title usmall">
                                        <a href="{{ route('frontend.blogs', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}">{{ $post->title }}</a>
                                    </h4>
                                </div>
                            </article>
                        @endforeach
                    </section>
                    <section class="widget widget_categories">
                        <h3 class="widget-title">{{ __('global.categories') }}</h3>
                        <ul>
                            @foreach ($article->categories ?? [] as $category)
                                <li> <a href="#">{{ $category }}</a></li>
                            @endforeach
                        </ul>
                    </section>
                    <section class="widget widget_tag_cloud">
                        <h3 class="widget-title">{{ __('global.tags') }}</h3>
                        <div class="tagcloud">
                            @foreach ($article->tags ?? [] as $tag)
                                <a href="#"> {{ ucfirst($tag) }} <span class="tag-link-count"> (1)</span></a>
                            @endforeach
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</section>
