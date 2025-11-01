@props(['teams' => [], 'title' => '', 'subtitle' => ''])
@if ($teams)
    <section class="team-area section-padding">
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
                @foreach ($teams as $team)
                    <div class="col-lg-3 col-md-6">
                        <div class="single-team-box" style="width: 250px;">
                            <div class="team-image" style="height: 280px; width: 100%; overflow: hidden; position: relative;">
                                @php
                                    $img = webp_variants($team->photo, 'portrait', null, 80);
                                @endphp
                                <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" sizes="(max-width: 768px) 95vw, {{ $img['width'] }}px" alt="{{ $team->name }}" loading="lazy" width="{{ $img['width'] }}" height="auto" style="height: 100%; width: 100%; object-fit: cover;">
                            </div>
                            <div class="team-info" style="text-align: center; padding: 15px;">
                                <h3>{{ $team->name }}</h3>
                                <span>{{ $team->position }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
