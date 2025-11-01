@props(['careers' => [], 'title' => '', 'subtitle' => ''])
<section class="services-section bg-grey section-padding">
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
            @foreach ($careers as $career)
                <div class="col-md-12">
                    <div class="single-services-item p-4">
                        <div class="d-flex justify-content-between">
                            <div class="flex-1">
                                <div class="d-flex align-items-center mb-1">
                                    <h3 class="text-primary me-2">{{ $career->title }}</h3>
                                    {!! badge($career->priority) !!}
                                </div>
                                <div class="d-flex align-items-start flex-column text-gray-600">
                                    <div class="d-flex align-items-center mb-1"><i class="fa-solid fa-user-group fa-fw"></i> &nbsp;<span>{{ $career->position }}</span></div>
                                </div>
                                <p class="text-gray-700 leading-relaxed m-0 mb-1">{{ $career->description }}</p>
                                <p class="text-gray-700 leading-relaxed m-0 mb-3"><i class="fa-jelly fa-solid fa-calendar fa-fw"></i> &nbsp;<span>Posted {{ $career->date ? date('F d, Y', strtotime($career->date)) : 'N/A' }}</span></p>
                                <a class="project-link-btn" href="{{ Route::has('frontend.careers') ? route('frontend.careers', ['locale' => app()->getLocale(), 'slug' => $career->slug]) : '#' }}">View &nbsp;<i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                {!! badge($career->type) !!}
                                {!! badge($career->level) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
