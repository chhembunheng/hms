@props(['partners' => [], 'title' => '', 'subtitle' => ''])
@if ($partners)
    <section class="partner-section pt-100 pb-70">
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
            </div>
            <div class="partner-list">
                <div class="partner-item">
                    <a href="index.html#0">
                        <img src="{{webpasset('site/assets/img/partner/client-1.png') }}" alt="image">
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif
