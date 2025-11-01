@props(['content' => ''])
@if ($content)
    <section class="privacy-policy ptb-100">
        <div class="container">
            <div class="single-privacy">
                {!! $content !!}
            </div>
        </div>
    </section>
@endif
