@if(isset($title))
    @section('title', $title)
@endif
@isset($meta?->title)
    @section('og:title', $meta->title)
    @section('twitter:title', $meta->title)
@endisset
@isset($meta?->image)
    @section('og:image', webpasset($meta->image))
    @section('twitter:image', webpasset($meta->image))
@endisset
@isset($meta?->keywords)
    @section('keywords', is_array($meta->keywords) ? implode(',', $meta->keywords) : $meta->keywords)
@endisset
@isset($meta?->description)
    @section('og:description', $meta->description)
    @section('twitter:description', $meta->description)
@endisset
@isset($title)
    <div class="page-title-area item-bg2">
        <div class="d-table">
            <div class="d-table-cell">
                <div class="container">
                    <div class="page-title-content">
                        <h2>{{ $title }}</h2>
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li>{{ $subtitle }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset
