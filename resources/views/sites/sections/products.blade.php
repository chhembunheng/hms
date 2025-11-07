@props(['products' => [], 'title' => '', 'subtitle' => ''])
@if ($products)
    <section class="project-area pt-70 pb-100">
        <div class="container">
            <div class="row">
                @if ($title || $subtitle)
                    <div class="col-md-12">
                        <article class="section-title">
                            @if ($title)
                                <h2>{{ $title }}</h2>
                            @endif
                            @if ($subtitle)
                                <p>{{ $subtitle }}</p>
                            @endif
                        </article>
                    </div>
                @endif
                @php
                    $total = count($products);
                    $remaining = $total % 3;
                    $latest = $total - $remaining;
                @endphp
                @foreach ($products as $product)
                    @php
                        $image = webpasset($product->thumb);
                        if (empty($image)) {
                            continue;
                        }
                        $colClass = 'col-lg-4 col-md-6';
                        if ($remaining == 2 && $loop->iteration > $latest) {
                            $colClass = 'col-lg-6 col-md-6';
                        }
                        if ($remaining == 1 && $loop->iteration > $latest) {
                            $colClass = 'col-12';
                        }
                    @endphp
                    <div class="{{ $colClass }}">
                        <article class="project-item">
                            @php
                                $img = webp_variants($image, 'product', null, 80);
                            @endphp
                            <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 768px) 95vw, {{ $img['width'] }}px" alt="{{ $product->name }}" loading="lazy" width="{{ $img['width'] }}" height="auto">
                            <div class="project-content-overlay">
                                <span class="project-category">{{ $product->category }}</span>
                                <h3 class="project-title">{{ $product->name }}</h3>
                                <p class="project-description">{!! $product->short_description ?? '' !!}</p>
                                <a class="project-link-btn" href="{{ Route::has('products') ? route('products', ['locale' => app()->getLocale(), 'slug' => $product->slug]) : '#' }}" aria-label="View Product {{ $product->name }}">{{ __('global.view_product') }}</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
