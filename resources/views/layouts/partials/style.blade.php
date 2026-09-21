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

/* Datepicker input styling - prevent theme .datepicker { display: none } from hiding inputs */
input.datepicker,
input.date-picker,
input.pickadate,
input.pick-adate,
input.picker__input {
    display: block !important;
    cursor: pointer;
    background-color: #ffffff;
}

.input-group > input.datepicker,
.input-group > input.date-picker,
.input-group > input.pickadate,
.input-group > input.pick-adate,
.input-group > input.picker__input {
    display: block !important;
    position: relative;
    flex: 1 1 auto;
    width: 1%;
    min-width: 0;
}

input.datepicker:focus,
input.date-picker:focus,
input.pickadate:focus,
input.pick-adate:focus,
input.picker__input:focus,
input.picker__input.picker__input--active {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-color: #86b7fe;
    background-color: #ffffff;
}

/* BELTEI style bg-darkblue utility */
.bg-darkblue {
    background-color: #002959 !important;
    color: #ffffff !important;
}
.bg-darkblue:hover {
    background-color: #001f44 !important;
    color: #ffffff !important;
}

/* Pickadate Dropdown Positioning & Modal Z-Index */
.picker {
    z-index: 10050 !important;
}

.picker__holder {
    outline: none;
    z-index: 10051 !important;
}

/* ─── Compact Layout Overrides ─────────────────────────────────── */
/* Reduce page content area padding */
.content {
    padding: 0.75rem 1rem !important;
}

/* Reduce container-fluid horizontal padding */
.container-fluid {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* Tighter card header */
.card-header {
    padding: 0.5rem 0.875rem !important;
}
.card-header h3,
.card-header h5,
.card-header h6,
.card-header .card-title {
    font-size: 0.875rem !important;
    margin-bottom: 0 !important;
}

/* Tighter card body */
.card-body {
    padding: 0.75rem 0.875rem !important;
}

/* Tighter filter card */
#filter-container .card-body {
    padding: 0.625rem 0.875rem !important;
}
#filter-container .card-header {
    padding: 0.4rem 0.875rem !important;
}
#filter-container .card-header h6 {
    font-size: 0.8125rem !important;
}
#filter-container .row.g-3 {
    --bs-gutter-x: 0.75rem;
    --bs-gutter-y: 0.5rem;
}
#filter-container .form-label {
    margin-bottom: 0.2rem !important;
    font-size: 0.8125rem;
}
#filter-container .d-flex.mt-3 {
    margin-top: 0.5rem !important;
    padding-top: 0.5rem !important;
}

/* Smaller form controls in filter */
#filter-container .form-control-sm,
#filter-container .form-select-sm {
    font-size: 0.8125rem;
}

/* Tighter DataTable card */
.card .card-body .card > .card-header {
    padding: 0.4rem 0.875rem !important;
}
.card .card-body .card > .card-body {
    padding: 0.5rem 0.875rem !important;
}

/* Reduce gap between filter and table */
.mb-3 {
    margin-bottom: 0.5rem !important;
}

</style>

{{-- Pickadate Theme CSS from beltei_ums --}}
<link rel="stylesheet" href="{{ asset('assets/extend/pickadate/themes/pickadate-limitless.css') }}?v={{ config('init.layout_version') }}">
