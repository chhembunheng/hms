@props(['faqs' => []])
@if ($faqs)
    <section class="faq-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="faq-accordion first-faq-box">
                        <ul class="accordion">
                            @foreach ($faqs as $index => $category)
                                @foreach ($category->faqs as $row)
                                    <li class="accordion-item mb-3">
                                        <a class="accordion-title" href="#{{ slug($category->slug) }}-{{ $row->id }}"> <i class="fa-solid fa-angle-right"></i> {{ $row->question }}</a>
                                        <p class="accordion-content">{{ $row->answer }}</p>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
@push('scripts')
    <script>
        const section = decodeURIComponent(location.hash.slice(1));
        if (section) {
            const target = $(`a[href="#${section}"]`).parent();
            if (target.length) {
                target.find('a.accordion-title').addClass('active');
                target.find('p.accordion-content').addClass('show');
            }
        }
    </script>
@endpush