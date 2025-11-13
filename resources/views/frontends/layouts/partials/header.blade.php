<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="{{ asset('site/assets/css/style.min.css') . (env('APP_ENV') == 'local' || request()->clear == 'yes' ? '?v=' . time() : '') }}" />
<link rel="stylesheet" href="{{ asset('site/assets/css/responsive.min.css') . (env('APP_ENV') == 'local' || request()->clear == 'yes' ? '?v=' . time() : '') }}" />
{!! file_get_contents(public_path('site/data/ld.html')) !!}