@props(['feedbacks' => []])
@if ($feedbacks)
    <section class="testimonial-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>Our Client Say</h2>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="testimonial-slider owl-carousel owl-theme">
                        @foreach ($feedbacks as $feedback)
                            <div class="single-testimonial">
                                <div class="rating-box">
                                    <ul>
                                        @php
                                            $rating = (float) ($feedback->rating ?? 0);
                                            $rounded = round($rating * 2) / 2;
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($rounded >= $i)
                                                <i class="fa-sharp fa-star text-success fa-fw"></i>
                                            @elseif ($rounded + 0.5 >= $i)
                                                <i class="fa-sharp fa-solid fa-star-sharp-half-stroke text-success fa-fw"></i>
                                            @else
                                                <i class="fa-sharp fa-solid fa-star-sharp text-success fa-fw"></i>
                                            @endif
                                        @endfor
                                    </ul>
                                </div>
                                <div class="testimonial-content">
                                    <p>{{ $feedback->content }}</p>
                                </div>
                                <div class="avatar">
                                    <img src="{{ webpasset($feedback->avatar) }}" alt="testimonial images">
                                </div>
                                <div class="testimonial-bio">
                                    <div class="bio-info">
                                        <h3>{{ $feedback->name }}</h3>
                                        <span>{{ $feedback->title }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
