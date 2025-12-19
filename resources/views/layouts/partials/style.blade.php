<link href="https://fonts.googleapis.com/css2?family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Hanuman:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/all.css') }}?v={{ config('init.layout_version') }}">
<link rel="stylesheet" href="{{ asset('assets/js/vendor/editors/tui/tui-image-editor.css') }}?v={{ config('init.layout_version') }}">
<link href="{{ asset('assets/css/all.min.css') }}?v={{ config('init.layout_version') }}" id="stylesheet" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/main.css') }}?v={{ config('init.layout_version') }}" id="stylesheet" rel="stylesheet" type="text/css">

<style>
/* Font system: Ubuntu first, Hanuman fallback for Khmer */
html:lang(km) {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html:lang(km) body,
html:lang(km) body * {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

/* English stays Ubuntu only */
html:lang(en) {
    font-family: 'Ubuntu', system-ui, -apple-system, sans-serif !important;
}

html:lang(en) body,
html:lang(en) body * {
    font-family: 'Ubuntu', system-ui, -apple-system, sans-serif !important;
}

/* Fallback using data-locale */
html[data-locale="km"] {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="km"] body,
html[data-locale="km"] body * {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="en"] {
    font-family: 'Ubuntu', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="en"] body,
html[data-locale="en"] body * {
    font-family: 'Ubuntu', system-ui, -apple-system, sans-serif !important;
}

/* Force Ubuntu first for all Bootstrap classes in Khmer locale */
html:lang(km) .navbar-brand,
html:lang(km) .nav-link,
html:lang(km) .dropdown-item,
html:lang(km) .btn,
html:lang(km) .form-control,
html:lang(km) .card-title,
html:lang(km) .card-text,
html:lang(km) h1, html:lang(km) h2, html:lang(km) h3,
html:lang(km) h4, html:lang(km) h5, html:lang(km) h6,
html:lang(km) p, html:lang(km) span, html:lang(km) div {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

/* English classes stay Ubuntu only */
html:lang(en) .navbar-brand,
html:lang(en) .nav-link,
html:lang(en) .dropdown-item,
html:lang(en) .btn,
html:lang(en) .form-control,
html:lang(en) .card-title,
html:lang(en) .card-text,
html:lang(en) h1, html:lang(en) h2, html:lang(en) h3,
html:lang(en) h4, html:lang(en) h5, html:lang(en) h6,
html:lang(en) p, html:lang(en) span, html:lang(en) div {
    font-family: 'Ubuntu', system-ui, -apple-system, sans-serif !important;
}

/* ABSOLUTELY PROTECT Font Awesome Icons - Highest Priority */
.fa, .fas, .far, .fal, .fad, .fab,
.fa-solid, .fa-regular, .fa-light, .fa-duotone, .fa-brands,
.fa-thin, .fa-sharp, .fa-classic,
[class*="fa-"] {
    font-family: "Font Awesome 7 Pro", "Font Awesome 7 Brands" !important;
    font-weight: normal !important;
    font-style: normal !important;
}

/* Prevent browser auto-translation */
html[translate="no"] {
    -webkit-user-translate: none !important;
    -moz-user-translate: none !important;
    -ms-user-translate: none !important;
    user-translate: none !important;
}

/* Hide from translation services */
@media screen and (-webkit-min-device-pixel-ratio: 0) {
    .notranslate {
        -webkit-user-translate: none !important;
    }
}

/* Extra protection for icons inside links and other elements */
html:lang(km) .fa, html:lang(km) .fas, html:lang(km) .far, html:lang(km) .fal,
html:lang(km) .fad, html:lang(km) .fab, html:lang(km) .fa-solid, html:lang(km) .fa-regular,
html:lang(km) .fa-light, html:lang(km) .fa-duotone, html:lang(km) .fa-brands,
html:lang(km) [class*="fa-"] {
    font-family: "Font Awesome 7 Pro", "Font Awesome 7 Brands" !important;
}

html:lang(en) .fa, html:lang(en) .fas, html:lang(en) .far, html:lang(en) .fal,
html:lang(en) .fad, html:lang(en) .fab, html:lang(en) .fa-solid, html:lang(en) .fa-regular,
html:lang(en) .fa-light, html:lang(en) .fa-duotone, html:lang(en) .fa-brands,
html:lang(en) [class*="fa-"] {
    font-family: "Font Awesome 7 Pro", "Font Awesome 7 Brands" !important;
}

/* Protect icons in navigation specifically */
.nav-link .fa, .nav-link .fas, .nav-link .far, .nav-link .fal,
.nav-link .fad, .nav-link .fab, .nav-link .fa-solid, .nav-link .fa-regular,
.nav-link .fa-light, .nav-link .fa-duotone, .nav-link .fa-brands,
.nav-link [class*="fa-"] {
    font-family: "Font Awesome 7 Pro", "Font Awesome 7 Brands" !important;
}
</style>
