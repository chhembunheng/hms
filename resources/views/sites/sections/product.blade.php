@props(['product' => null, 'title' => '', 'subtitle' => ''])
@php
    $locale = app()->getLocale();
@endphp
@if ($product)
    <section class="product-detail-section py-5">
        <div class="container">
            <div class="row">
                <!-- Product Gallery -->
                <div class="col-lg-6 mb-4">
                    <div class="product-gallery">
                        <!-- Main Image -->
                        <div class="main-image-container position-relative mb-3 rounded-3 overflow-hidden"
                            style="background: #f8f9fa; min-height: 500px;">
                            <img id="mainImage" src="{{ asset($product->image) }}" alt="{{ $product->getName($locale) }}"
                                class="w-100 h-100" style="object-fit: contain; padding: 20px;">
                        </div>

                        <!-- Thumbnail Gallery -->
                        @if ($product->images && count($product->images) > 0)
                            <div class="thumbnail-gallery">
                                <div class="row g-2">
                                    <div class="col-3">
                                        <div class="thumbnail-item cursor-pointer rounded-2 overflow-hidden border border-2 border-primary"
                                            onclick="changeMainImage(this)">
                                            <img src="{{ asset($product->image) }}" alt="Main" class="w-100 h-auto"
                                                style="min-height: 100px; object-fit: cover;">
                                        </div>
                                    </div>
                                    @forelse ($product->images as $image)
                                        <div class="col-3">
                                            <div class="thumbnail-item cursor-pointer rounded-2 overflow-hidden border"
                                                onclick="changeMainImage(this)">
                                                <img src="{{ asset($image['url']) }}"
                                                    alt="{{ $image['alt'] ?? 'Product' }}" class="w-100 h-auto"
                                                    style="min-height: 100px; object-fit: cover;">
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Product Information -->
                <div class="col-lg-6">
                    <!-- Title & Description -->
                    <div class="product-header mb-4">
                        <h1 class="display-5 fw-bold mb-3 text-dark">{{ $product->getName($locale) ?? '' }}</h1>

                        @if ($product->getDescription($locale))
                            <p class="lead text-muted mb-3">{!! $product->getDescription($locale) !!}</p>
                        @endif

                        <!-- Product Meta -->
                        <div class="product-meta d-flex flex-wrap gap-3 mb-4 pb-4 border-bottom">
                            @if ($product->icon)
                                <div class="meta-item">
                                    <i class="{{ $product->icon }} fa-2x text-primary me-2"></i>
                                </div>
                            @endif
                            <div class="meta-item">
                                <small class="text-muted d-block">{{ __('global.product_id') }}</small>
                                <strong>#{{ $product->id }}</strong>
                            </div>
                            <div class="meta-item">
                                <small class="text-muted d-block">{{ __('global.availability') }}</small>
                                <strong class="text-success">{{ __('global.in_stock') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons mb-4">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-primary btn-lg rounded-start-3 rounded-0"
                                onclick="downloadProductInfo()">
                                <i class="fa-solid fa-download me-2"></i>{{ __('global.download_info') }}
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg rounded-end-3 rounded-0"
                                onclick="contactAboutProduct()">
                                <i class="fa-solid fa-envelope me-2"></i>{{ __('global.contact_us') }}
                            </button>
                        </div>
                    </div>

                    <!-- Share Section -->
                    <div class="share-section p-3 bg-light rounded-2 mb-4">
                        <x-share :url="url()->current()" :title="$product->getName($locale)" />
                    </div>
                </div>
            </div>
            <h1>{{ __('global.overview') }}</h1>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="content-text">
                        {!! $product->getContent($locale) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<style>
    .product-detail-section {
        background: #f8f9fa;
    }

    .main-image-container {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .product-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
    }

    .action-buttons .btn-group {
        display: flex;
        gap: 0;
    }

    .action-buttons .btn {
        flex: 1;
        border-radius: 0 !important;
        padding: 0.75rem 1rem;
        font-weight: 600;
    }

    .action-buttons .btn:first-child {
        border-radius: 20px 0 0 20px !important;
    }

    .action-buttons .btn:last-child {
        border-radius: 0 20px 20px 0 !important;
    }

    .content-text {
        color: #555;
        font-size: 1.05rem;
        line-height: 1.8;
    }

    .content-text p {
        margin-bottom: 1rem;
    }

    .content-text ul,
    .content-text ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }

    .content-text li {
        margin-bottom: 0.5rem;
    }

    .feature-card {
        transition: all 0.3s ease;
        border-color: #dee2e6 !important;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        border-color: #667eea !important;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #667eea;
        color: #667eea;
    }

    .nav-tabs .nav-link.active {
        border-bottom-color: #667eea;
        color: #667eea;
        background: transparent;
    }

    .cta-section {
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
    }

    .btn-outline-primary:hover {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
    }

    @media (max-width: 768px) {
        .product-detail-section {
            padding: 1rem 0;
        }

        .display-5 {
            font-size: 1.75rem !important;
        }

        .lead {
            font-size: 1rem !important;
        }

        .action-buttons .btn {
            min-height: 45px;
        }

        .nav-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .nav-tabs .nav-link {
            white-space: nowrap;
        }
    }
</style>

<script>
    function changeMainImage(element) {
        const img = element.querySelector('img');
        const mainImage = document.getElementById('mainImage');
        mainImage.src = img.src;

        // Update active thumbnail
        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('border-primary', 'border-2');
            item.classList.add('border');
        });
        element.classList.add('border-primary', 'border-2');
        element.classList.remove('border');
    }

    function contactAboutProduct() {
        const modal = new bootstrap.Modal(document.getElementById('contactModal'));
        modal.show();
    }

    function downloadProductInfo() {
        // Implement download functionality
        alert('Download functionality would be implemented here');
    }

    // Handle contact form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('productContactForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                // Implement form submission via AJAX
                alert('Contact form submission would be handled here');
            });
        }
    });
</script>
