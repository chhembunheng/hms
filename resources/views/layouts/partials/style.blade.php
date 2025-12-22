<link href="https://fonts.googleapis.com/css2?family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Hanuman:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/all.css') }}?v={{ config('init.layout_version') }}">
<link rel="stylesheet" href="{{ asset('assets/js/vendor/editors/tui/tui-image-editor.css') }}?v={{ config('init.layout_version') }}">
<link href="{{ asset('assets/css/all.min.css') }}?v={{ config('init.layout_version') }}" id="stylesheet" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/main.css') }}?v={{ config('init.layout_version') }}" id="stylesheet" rel="stylesheet" type="text/css">

<style>
html:lang(km) {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html:lang(km) body,
html:lang(km) body * {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html:lang(en) {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html:lang(en) body,
html:lang(en) body * {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="km"] {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="km"] body,
html[data-locale="km"] body * {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="en"] {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="en"] body,
html[data-locale="en"] body * {
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

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
    font-family: 'Ubuntu', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

.fa, .fas, .far, .fal, .fad, .fab,
.fa-solid, .fa-regular, .fa-light, .fa-duotone, .fa-brands,
.fa-thin, .fa-sharp, .fa-classic,
[class*="fa-"] {
    font-family: "Font Awesome 7 Pro", "Font Awesome 7 Brands" !important;
    font-weight: normal !important;
    font-style: normal !important;
}

html[translate="no"] {
    -webkit-user-translate: none !important;
    -moz-user-translate: none !important;
    -ms-user-translate: none !important;
    user-translate: none !important;
}

@media screen and (-webkit-min-device-pixel-ratio: 0) {
    .notranslate {
        -webkit-user-translate: none !important;
    }
}

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

.nav-link .fa, .nav-link .fas, .nav-link .far, .nav-link .fal,
.nav-link .fad, .nav-link .fab, .nav-link .fa-solid, .nav-link .fa-regular,
.nav-link .fa-light, .nav-link .fa-duotone, .nav-link .fa-brands,
.nav-link [class*="fa-"] {
    font-family: "Font Awesome 7 Pro", "Font Awesome 7 Brands" !important;
}

/* Custom Brand Color: #034246 (Dark Teal) */
:root {
    --custom-brand-color: #034246;
    --custom-brand-hover: #025a5f;
    --custom-brand-light: #e8f4f5;
}

/* Apply custom color to navbar */
.navbar-dark {
    background-color: var(--custom-brand-color) !important;
}

/* Apply to nav links */
.nav-link {
    color: var(--custom-brand-color) !important;
}

/* Apply to primary buttons */
.btn-primary {
    background-color: var(--custom-brand-color) !important;
    border-color: var(--custom-brand-color) !important;
}

.btn-primary:hover {
    background-color: var(--custom-brand-hover) !important;
    border-color: var(--custom-brand-hover) !important;
}

/* Apply to active states */
.nav-link.active,
.nav-sidebar .nav-link.active {
    background-color: var(--custom-brand-color) !important;
    color: white !important;
}

/* Apply to focus states */
.btn-primary:focus,
.btn-primary:active {
    background-color: var(--custom-brand-hover) !important;
    border-color: var(--custom-brand-hover) !important;
    box-shadow: 0 0 0 0.2rem rgba(3, 66, 70, 0.25) !important;
}

/* Apply to accent elements */
.text-primary {
    color: var(--custom-brand-color) !important;
}

.bg-primary {
    background-color: var(--custom-brand-color) !important;
}

.border-primary {
    border-color: var(--custom-brand-color) !important;
}

/* Custom gradient backgrounds */
.custom-gradient {
    background: linear-gradient(135deg, var(--custom-brand-color) 0%, var(--custom-brand-hover) 100%);
}

/* Sidebar active states */
.nav-sidebar .nav-link.active {
    background: linear-gradient(135deg, var(--custom-brand-color) 0%, var(--custom-brand-hover) 100%) !important;
    color: white !important;
}

/* Datepicker styling */
.datepicker {
    cursor: pointer;
}

.datepicker:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-color: #86b7fe;
}

/* Datepicker dropdown styling */
.datepicker-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.datepicker .datepicker-cell {
    padding: 8px 12px;
    cursor: pointer;
}

.datepicker .datepicker-cell:hover {
    background-color: #f8f9fa;
}

.datepicker .datepicker-cell.selected {
    background-color: #0d6efd;
    color: white;
}

.datepicker .datepicker-cell.today {
    background-color: #fff3cd;
}

</style>

{{-- Pickadate CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/default.css') }}?v={{ config('init.layout_version') }}">
<link rel="stylesheet" href="{{ asset('assets/css/default.date.css') }}?v={{ config('init.layout_version') }}">
