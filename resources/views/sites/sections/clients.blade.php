@props(['clients' => [], 'title' => '', 'subtitle' => ''])
@if ($clients)
    <section class="partner-section bg-color pt-100 pb-70">
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
                @foreach ($clients as $client)
                    <div class="partner-item">
                        <a href="#">
                            <img src="{{ webpasset($client) }}" alt="image">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif