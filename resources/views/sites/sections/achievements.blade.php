@props(['achievements' => [], 'title' => '', 'subtitle' => ''])
@if ($achievements)
    <section class="counter-area section-padding" style="background: url('{{ webpasset('site/assets/img/bg/achievement.png') }}') center/cover no-repeat;">
        <div class="container">
            <div class="row">
                @if ($title || $subtitle)
                    <div class="col-md-12">
                        <div class="section-title">
                            @if ($title)
                                <h3 style="color: white">{{ $title }}</h3>
                            @endif
                            @if ($subtitle)
                                <p style="color: white">{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                @endif
                @foreach ($achievements as $achievement)
                    <div class="col-lg-3 col-md-6 counter-item">
                        <div class="single-counter">
                            <div class="counter-contents">
                                <h2 style="display: flex; align-items: center; justify-content: center;">
                                    <i class="{{ $achievement->icon }} fa-fw" style="font-size: 35px;"></i> &nbsp;
                                    <span class="counter-number" data-target="{{ $achievement->number }}">0</span>
                                    <span>{{ $achievement->suffix }}</span>
                                </h2>
                                <h3 class="counter-heading">{{ $achievement->title }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
