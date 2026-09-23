<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Hanuman:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/js/vendor/editors/tui/tui-image-editor.css') }}?v={{ config('init.layout_version') }}">
<link href="{{ asset('assets/fonts/fontawesome/css/all.css') }}?v={{ config('init.layout_version') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/all.min.css') }}?v={{ config('init.layout_version') }}" id="stylesheet" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/main.css') }}?v={{ config('init.layout_version') }}" id="stylesheet" rel="stylesheet" type="text/css">

<style>
/* Local Outfit Font Definitions */
@font-face {
  font-family: 'Outfit';
  font-style: normal;
  font-weight: 300 700;
  font-display: swap;
  src: url('{{ asset("assets/fonts/outfit/outfit-latin.woff2") }}') format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
@font-face {
  font-family: 'Outfit';
  font-style: normal;
  font-weight: 300 700;
  font-display: swap;
  src: url('{{ asset("assets/fonts/outfit/outfit-latin-ext.woff2") }}') format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

html:lang(km) {
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html:lang(km) body,
html:lang(km) body * {
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html:lang(en) {
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html:lang(en) body,
html:lang(en) body * {
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="km"] {
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="km"] body,
html[data-locale="km"] body * {
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="en"] {
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
}

html[data-locale="en"] body,
html[data-locale="en"] body * {
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
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
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
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
    font-family: 'Outfit', 'Hanuman', system-ui, -apple-system, sans-serif !important;
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

/* Custom Brand Color: Coloro / WGSN 2027 "Luminous Blue" (125-28-38) */
:root {
    --custom-brand-color: #193f8f;
    --custom-brand-hover: #132f6b;
    --custom-brand-light: #edf3fc;
    --custom-brand-active: #193f8f;
}

/* Apply custom color to navbar */
.navbar-dark {
    background-color: #193f8f !important;
}

/* Apply to primary buttons */
.btn-primary {
    background-color: #193f8f !important;
    border-color: #193f8f !important;
}

.btn-primary:hover {
    background-color: #132f6b !important;
    border-color: #132f6b !important;
}

/* Apply to active states */
.nav-sidebar .nav-link.active {
    background-color: #edf3fc !important;
    color: #193f8f !important;
}

/* Apply to focus states */
.btn-primary:focus,
.btn-primary:active {
    background-color: #132f6b !important;
    border-color: #132f6b !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 63, 143, 0.25) !important;
}

/* Apply to accent elements */
.text-primary {
    color: #193f8f !important;
}

.bg-primary {
    background-color: #193f8f !important;
}

.border-primary {
    border-color: #193f8f !important;
}

/* Lucide SVG Icon Global Rules */
svg.lucide,
i[data-lucide] svg,
.lucide {
    width: 1.15em;
    height: 1.15em;
    stroke-width: 2;
    vertical-align: -0.15em;
    display: inline-block;
}

/* Modern Header Styling (Luminous Blue Signature) */
.navbar {
    background: #193f8f !important; /* Coloro 125-28-38 Luminous Blue */
    border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08) !important;
}

/* Beltei UMS Sidebar Pattern (Clean White & Silky Smooth) */
:root {
    --sidebar-width: 256px !important;
}

.sidebar {
    --sidebar-width: 256px !important;
}

.sidebar-main {
    width: 256px !important;
    min-width: 256px !important;
    max-width: 256px !important;
    flex: 0 0 256px !important;
    background-color: #ffffff !important;
    border-right: 1px solid #eef2f6 !important;
    box-shadow: none !important;
    transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1), min-width 0.2s cubic-bezier(0.4, 0, 0.2, 1), max-width 0.2s cubic-bezier(0.4, 0, 0.2, 1), flex-basis 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.content-wrapper {
    flex: 1 1 0% !important;
    min-width: 0 !important;
}

@media (max-width: 991.98px) {
    .sidebar-main {
        width: 260px !important;
        min-width: 260px !important;
        max-width: 85vw !important;
        flex: none !important;
    }
}

/* Prevent any full-hiding collapse from breaking layout */
body .sidebar-main.sidebar-collapsed,
body .sidebar-expand-lg.sidebar-collapsed {
    display: flex !important;
}
body .sidebar-expand-lg.sidebar-collapsed > :not(.btn-sidebar-expand) {
    display: flex !important;
}

.sidebar-main .nav-sidebar {
    padding: 0.5rem 0.65rem !important;
}

.sidebar-main .nav-sidebar > .nav-item {
    margin-bottom: 2px !important;
}

.sidebar-main .nav-sidebar > .nav-item > .nav-link {
    position: relative;
    display: flex !important;
    align-items: center;
    color: #334155 !important;
    padding: 0.55rem 0.85rem !important;
    font-size: 0.84rem !important;
    font-weight: 500;
    border-radius: 8px !important;
    margin: 0 !important;
    border: none !important;
    background-color: transparent !important;
    transition: all 0.15s ease !important;
}

.sidebar-main .nav-sidebar > .nav-item > .nav-link:hover {
    color: #193f8f !important;
    background-color: #edf3fc !important;
}

/* Active Top-Level Link & Open Parent (Beltei Pattern: Soft Luminous Blue + Left Accent Bar) */
.sidebar-main .nav-sidebar > .nav-item > .nav-link.active,
.sidebar-main .nav-sidebar > .nav-item.nav-item-open > .nav-link {
    background-color: #edf3fc !important;
    color: #193f8f !important;
    font-weight: 600 !important;
}

.sidebar-main .nav-sidebar > .nav-item > .nav-link.active::before,
.sidebar-main .nav-sidebar > .nav-item.nav-item-open > .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3.5px;
    background-color: #193f8f;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

/* Font Awesome & Proportional Icons in Navigation */
.sidebar-main .nav-sidebar .nav-link .nav-link-icon,
.sidebar-main .nav-sidebar .nav-link i.fa-fw,
.sidebar-main .nav-sidebar .nav-link i[class*="fa-"],
.sidebar-main .nav-sidebar .nav-link svg.lucide,
.sidebar-main .nav-sidebar .nav-link [data-lucide] {
    font-size: 0.95rem !important;
    width: 1.25rem !important;
    text-align: center;
    margin-right: 0.65rem !important;
    color: #475569;
    flex-shrink: 0;
    vertical-align: middle;
    display: inline-block;
    transition: color 0.15s ease, stroke 0.15s ease;
}

.sidebar-main .nav-sidebar .nav-link:hover i[class*="fa-"],
.sidebar-main .nav-sidebar .nav-link.active i[class*="fa-"],
.sidebar-main .nav-sidebar .nav-item.nav-item-open > .nav-link i[class*="fa-"],
.sidebar-main .nav-sidebar .nav-link:hover .nav-link-icon,
.sidebar-main .nav-sidebar .nav-link.active .nav-link-icon,
.sidebar-main .nav-sidebar .nav-item.nav-item-open > .nav-link .nav-link-icon {
    color: #193f8f !important;
    stroke: #193f8f !important;
}

.nav-link-title {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Submenu Chevron Arrow Indicator */
.sidebar-main .nav-item-submenu > .nav-link:first-child {
    border-top: none !important;
}

.sidebar-main .nav-item-submenu > .nav-link:after {
    content: "" !important;
    font-family: inherit !important;
    display: block !important;
    width: 5px !important;
    height: 5px !important;
    border-right: 1.6px solid #94a3b8 !important;
    border-bottom: 1.6px solid #94a3b8 !important;
    border-top: none !important;
    border-left: none !important;
    transform: rotate(-45deg) !important;
    position: absolute !important;
    right: 0.85rem !important;
    top: 50% !important;
    margin-top: -3px !important;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.15s ease !important;
}

.sidebar-main .nav-item-submenu.nav-item-open > .nav-link:after {
    transform: rotate(45deg) !important;
    border-color: #193f8f !important;
    margin-top: -4px !important;
}

/* Submenu Container (Beltei Pattern: Seamless & Clean) */
.sidebar-main .nav-group-sub {
    background-color: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    margin: 0 !important;
    padding: 2px 0 3px 0 !important;
    list-style: none !important;
}

/* Submenu Child Links */
.sidebar-main .nav-group-sub .nav-item {
    margin-bottom: 1px !important;
}

.sidebar-main .nav-group-sub .nav-link {
    position: relative;
    display: flex !important;
    align-items: center;
    padding: 0.45rem 0.75rem 0.45rem 1.85rem !important;
    font-size: 0.8125rem !important;
    color: #475569 !important;
    font-weight: 400 !important;
    border: none !important;
    border-radius: 6px !important;
    margin: 0 !important;
    transition: all 0.15s ease !important;
}

.sidebar-main .nav-group-sub .nav-link:hover {
    color: #193f8f !important;
    background-color: #edf3fc !important;
    font-weight: 500 !important;
}

/* Active Submenu Child Link (e.g. Staying Guests) */
.sidebar-main .nav-group-sub .nav-link.active {
    background-color: #edf3fc !important;
    color: #193f8f !important;
    font-weight: 600 !important;
}

.sidebar-main .nav-group-sub .nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3.5px;
    background-color: #193f8f;
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
}

.sidebar-main .nav-group-sub .nav-link .nav-link-icon {
    width: 13px !important;
    height: 13px !important;
    max-width: 13px !important;
    max-height: 13px !important;
    stroke: #64748b !important;
    stroke-width: 1.5 !important;
    margin-right: 0.65rem !important;
}

.sidebar-main .nav-group-sub .nav-link:hover .nav-link-icon,
.sidebar-main .nav-group-sub .nav-link.active .nav-link-icon {
    stroke: #193f8f !important;
}

/* Beltei-style Sidebar Search Box */
.sidebar-search-container {
    padding: 0.75rem 0.75rem 0.4rem 0.75rem !important;
}

.sidebar-search-container .search-container {
    position: relative;
}

#search-menu {
    padding: 6px 28px 6px 32px !important;
    font-size: 12.5px;
    height: 34px;
    border: 1px solid #e2e8f0 !important;
    border-radius: 17px !important;
    background-color: #f8fafc !important;
    color: #334155;
    transition: all 0.2s ease;
    box-shadow: none !important;
}

#search-menu:focus {
    background-color: #ffffff !important;
    border-color: #193f8f !important;
    box-shadow: 0 0 0 3px rgba(25, 63, 143, 0.1) !important;
    outline: none;
}

#search-menu::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

.search-icon-wrapper {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    width: 14px;
    height: 14px;
    pointer-events: none;
    z-index: 5;
    transition: color 0.2s ease;
}

#search-menu:focus + .search-icon-wrapper,
.search-container:focus-within .search-icon-wrapper {
    color: #193f8f;
}

.btn-clear-search {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 6;
}
.btn-clear-search:hover {
    color: #e11d48;
}

/* Beltei Modern Autocomplete Dropdown */
#autocomplete-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    z-index: 1050;
    border: 1px solid #e2e8f0;
    background-color: #ffffff;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    max-height: 300px;
    overflow-y: auto;
    padding: 4px;
    border-radius: 10px;
    animation: sidebarSlideDown 0.15s ease;
}

@keyframes sidebarSlideDown {
    from {
        opacity: 0;
        transform: translateY(-6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#autocomplete-dropdown::-webkit-scrollbar {
    width: 4px;
}
#autocomplete-dropdown::-webkit-scrollbar-track {
    background: #f8fafc;
}
#autocomplete-dropdown::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

#autocomplete-dropdown .dropdown-item {
    padding: 6px 10px;
    border-radius: 6px;
    margin-bottom: 2px;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: normal;
    word-wrap: break-word;
    font-size: 12.5px;
    color: #334155;
    display: flex;
    align-items: center;
    border-left: 3px solid transparent;
}

#autocomplete-dropdown .dropdown-item:hover,
#autocomplete-dropdown .dropdown-item.active {
    background: #edf3fc;
    color: #193f8f;
    border-left-color: #193f8f;
}

/* Silky Slim Scrollbar for Sidebar */
.sidebar-content .overflow-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
.sidebar-content .overflow-auto::-webkit-scrollbar {
    width: 4px;
}
.sidebar-content .overflow-auto::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar-content .overflow-auto::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.sidebar-content .overflow-auto:hover::-webkit-scrollbar-thumb {
    background: #cbd5e1;
}

/* Sidebar Footer */
.sidebar-footer {
    background: #fbfcfe !important;
    border-top: 1px solid #eef2f6 !important;
}

/* ─────────────────────────────────────────────────────────────
   BELTEI UMS COLLAPSED SIDEBAR (sidebar-xs)
   ───────────────────────────────────────────────────────────── */

body.sidebar-xs .sidebar-main {
    width: 56px !important;
    min-width: 56px !important;
    max-width: 56px !important;
    flex: 0 0 56px !important;
    overflow: visible !important;
}

body.sidebar-xs .sidebar-content {
    overflow: visible !important;
}

body.sidebar-xs .sidebar-content .overflow-auto {
    overflow: visible !important;
}

body.sidebar-xs .sidebar-search-container,
body.sidebar-xs .sidebar-footer {
    display: none !important;
}

body.sidebar-xs .sidebar-main .nav-sidebar {
    padding: 0.5rem 0.25rem !important;
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item > .nav-link {
    padding: 0.65rem 0 !important;
    justify-content: center !important;
    text-align: center !important;
    border-radius: 8px !important;
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item > .nav-link::before {
    display: none !important;
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item > .nav-link .nav-link-title,
body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu > .nav-link:after {
    display: none !important;
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item > .nav-link .nav-link-icon {
    margin-right: 0 !important;
    width: 16px !important;
    height: 16px !important;
    max-width: 16px !important;
    max-height: 16px !important;
}

/* Collapsed Flyout Submenu on Hover */
body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu {
    position: relative !important;
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu > .nav-group-sub {
    position: absolute !important;
    left: 54px !important;
    top: 0 !important;
    width: 210px !important;
    background-color: #ffffff !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12) !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 0 8px 8px 0 !important;
    display: none !important;
    z-index: 1060 !important;
    padding: 6px !important;
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu:hover > .nav-group-sub {
    display: block !important;
    animation: sidebarFlyout 0.15s ease !important;
}

@keyframes sidebarFlyout {
    from {
        opacity: 0;
        transform: translateX(-5px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu > .nav-group-sub[data-submenu-title]:before {
    content: attr(data-submenu-title);
    display: block;
    padding: 0.5rem 0.75rem 0.35rem 0.75rem;
    font-size: 0.73rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #193f8f;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 4px;
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu > .nav-group-sub .nav-link {
    padding: 0.45rem 0.75rem !important;
    font-size: 0.8125rem !important;
    border-radius: 6px !important;
}

body.sidebar-xs .sidebar-main .nav-sidebar > .nav-item-submenu > .nav-group-sub .nav-link:hover {
    background-color: #edf3fc !important;
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

/* ─────────────────────────────────────────────────────────────
   ENTERPRISE SYSTEM UNIFIED TEMPLATES & COMPONENTS
   ───────────────────────────────────────────────────────────── */

.enterprise-card {
    background-color: #ffffff;
    border: 1px solid #eef2f6 !important;
    border-radius: 10px !important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03) !important;
    overflow: visible;
}

.enterprise-card-header {
    background-color: #ffffff !important;
    border-bottom: 1px solid #f1f5f9 !important;
    padding: 0.65rem 1rem !important;
}

.enterprise-header-icon {
    width: 32px;
    height: 32px;
    background-color: #edf3fc;
    color: #193f8f;
    flex-shrink: 0;
}

/* Modern Soft Toolbar Buttons */
.btn-enterprise-action {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.35rem 0.75rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #475569;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    transition: all 0.15s ease-in-out;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    cursor: pointer;
    line-height: 1.4;
    text-decoration: none;
}

.btn-enterprise-action:hover {
    color: #0f172a;
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
}

.btn-enterprise-action:active,
.btn-enterprise-action.active {
    color: #193f8f;
    background-color: #edf3fc;
    border-color: #c7d8f5;
    box-shadow: inset 0 1px 2px rgba(25, 63, 143, 0.08);
}

.btn-enterprise-action i[data-lucide] {
    stroke-width: 1.8;
}

.btn-enterprise-icon-btn {
    padding: 0.35rem 0.55rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* ─────────────────────────────────────────────────────────────
   ENTERPRISE FILTER SYSTEM & FORM CONTROLS (ULTRA-SMOOTH)
   ───────────────────────────────────────────────────────────── */

#enterprise-filter-panel {
    background-color: #fafbfc !important;
    border-bottom: 1px solid #edf2f7 !important;
    position: relative;
    z-index: 1050;
    overflow: hidden;
    padding: 0 !important;
}

#enterprise-filter-panel.is-open {
    overflow: visible !important;
}

.enterprise-filter-inner {
    padding: 1rem 1.25rem !important;
}

.enterprise-filter-canvas {
    background-color: transparent !important;
}

/* Filter Labels */
#filter-container .form-label,
#enterprise-filter-panel .form-label {
    font-size: 0.73rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    color: #64748b !important;
    margin-bottom: 0.35rem !important;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

/* Filter Form Inputs & Selects */
#filter-container .form-control,
#filter-container .form-control-sm,
#filter-container .form-select,
#filter-container .form-select-sm,
#enterprise-filter-panel .form-control,
#enterprise-filter-panel .form-select {
    height: 36px !important;
    font-size: 0.8125rem !important;
    color: #1e293b !important;
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    padding: 0.45rem 0.75rem !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03) !important;
    transition: all 0.15s ease !important;
}

#filter-container .form-control:hover,
#filter-container .form-select:hover,
#enterprise-filter-panel .form-control:hover,
#enterprise-filter-panel .form-select:hover {
    border-color: #cbd5e1 !important;
}

#filter-container .form-control:focus,
#filter-container .form-select:focus,
#enterprise-filter-panel .form-control:focus,
#enterprise-filter-panel .form-select:focus {
    border-color: #193f8f !important;
    background-color: #ffffff !important;
    box-shadow: 0 0 0 3px rgba(25, 63, 143, 0.12) !important;
    outline: none !important;
}

#filter-container .form-control::placeholder {
    color: #94a3b8 !important;
    font-weight: 400;
}

/* Multiselect Trigger Button in Filter */
#filter-container .btn-group > button.multiselect,
#enterprise-filter-panel .btn-group > button.multiselect {
    height: 36px !important;
    line-height: 1.4 !important;
    font-size: 0.8125rem !important;
    font-weight: 400 !important;
    color: #1e293b !important;
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    padding: 0.45rem 0.75rem !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    transition: all 0.15s ease !important;
    text-align: left !important;
}

#filter-container .btn-group > button.multiselect:hover,
#enterprise-filter-panel .btn-group > button.multiselect:hover {
    border-color: #cbd5e1 !important;
    background-color: #f8fafc !important;
}

#filter-container .btn-group.show > button.multiselect,
#filter-container .btn-group > button.multiselect:focus,
#enterprise-filter-panel .btn-group.show > button.multiselect,
#enterprise-filter-panel .btn-group > button.multiselect:focus {
    border-color: #193f8f !important;
    box-shadow: 0 0 0 3px rgba(25, 63, 143, 0.12) !important;
    outline: none !important;
}

/* Multiselect Dropdown Container */
.multiselect-container {
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.04) !important;
    padding: 0.4rem !important;
    min-width: 220px;
    z-index: 1080 !important;
    background: #ffffff !important;
}

.multiselect-container > li > a {
    padding: 0.4rem 0.65rem !important;
    border-radius: 6px !important;
    font-size: 0.8125rem !important;
    font-weight: 500 !important;
    color: #334155 !important;
    transition: background-color 0.12s ease !important;
    display: flex;
    align-items: center;
}

.multiselect-container > li > a:hover,
.multiselect-container > li.active > a {
    background-color: #edf3fc !important;
    color: #193f8f !important;
}

.multiselect-container input[type="checkbox"] {
    accent-color: #193f8f !important;
    margin-right: 0.5rem;
}

/* Filter Actions Bar */
.filter-actions-bar {
    border-top: 1px solid #edf2f7 !important;
    margin-top: 1rem !important;
    padding-top: 0.85rem !important;
}

/* Modern Ghost Reset Button */
.btn-filter-reset {
    height: 34px !important;
    padding: 0 0.95rem !important;
    font-size: 0.8125rem !important;
    font-weight: 500 !important;
    color: #64748b !important;
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02) !important;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer;
    text-decoration: none;
}

.btn-filter-reset:hover {
    background-color: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(15, 23, 42, 0.05) !important;
}

.btn-filter-reset:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02) !important;
}

/* Modern Brand Apply Button */
.btn-filter-apply {
    height: 34px !important;
    padding: 0 1.25rem !important;
    font-size: 0.8125rem !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    background: linear-gradient(135deg, #193f8f 0%, #112d69 100%) !important;
    border: 1px solid #193f8f !important;
    border-radius: 8px !important;
    box-shadow: 0 2px 6px rgba(25, 63, 143, 0.28) !important;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer;
    text-decoration: none;
}

.btn-filter-apply:hover {
    background: linear-gradient(135deg, #143373 0%, #0c2049 100%) !important;
    border-color: #143373 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(25, 63, 143, 0.38) !important;
    transform: translateY(-1px);
}

.btn-filter-apply:active {
    transform: translateY(0);
    box-shadow: 0 1px 3px rgba(25, 63, 143, 0.2) !important;
}

/* ─────────────────────────────────────────────────────────────
   PREMIUM ENTERPRISE FORM CONTROLS & FORM SYSTEM
   ───────────────────────────────────────────────────────────── */

.enterprise-form-card {
    border-radius: 12px !important;
    background: #ffffff !important;
    border: 1px solid #eef2f6 !important;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important;
    position: relative;
    z-index: 1;
    overflow: hidden;
}

.enterprise-form-card .card-header {
    background-color: #ffffff !important;
    border-bottom: 1px solid #eef2f6 !important;
    padding: 0.85rem 1.5rem !important;
}

.enterprise-form-card .card-body {
    padding: 1.5rem !important;
}

.enterprise-form-card .card-footer {
    background-color: #f8fafc !important;
    border-top: 1px solid #eef2f6 !important;
    padding: 0.85rem 1.5rem !important;
}

.enterprise-form-hint {
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-left: 3.5px solid #193f8f !important;
    border-radius: 6px !important;
    padding: 0.6rem 0.9rem !important;
    font-size: 0.8125rem !important;
    color: #475569 !important;
}

.enterprise-header-icon {
    width: 34px !important;
    height: 34px !important;
    background: #edf3fc !important;
    color: #193f8f !important;
    border-radius: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
}

/* General Form Labels */
.form-label {
    font-size: 0.82rem;
    font-weight: 500;
    color: #334155;
    margin-bottom: 0.35rem;
}

.form-label.required::after,
.required::after {
    content: " *";
    color: #e11d48;
    font-weight: 700;
}

/* Enterprise Form Inputs & Selects */
.enterprise-form .form-control,
.enterprise-form .form-select,
.enterprise-card .form-control,
.enterprise-card .form-select {
    height: 38px !important;
    font-size: 0.85rem !important;
    font-weight: 400 !important;
    color: #1e293b !important;
    background-color: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 0.45rem 0.85rem !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
}

.enterprise-form textarea.form-control,
.enterprise-card textarea.form-control {
    height: auto !important;
    min-height: 85px !important;
}

.enterprise-form .form-control:focus,
.enterprise-form .form-select:focus,
.enterprise-card .form-control:focus,
.enterprise-card .form-select:focus {
    border-color: #193f8f !important;
    box-shadow: 0 0 0 3px rgba(25, 63, 143, 0.12) !important;
    outline: none !important;
}

/* Select2 Dropdown Matching Form Inputs 1:1 */
.enterprise-form .select2-container,
.enterprise-card .select2-container {
    width: 100% !important;
}

.enterprise-form .select2-container--default .select2-selection--single,
.enterprise-card .select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--single {
    height: 38px !important;
    font-size: 0.85rem !important;
    background-color: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    padding-left: 0.85rem !important;
    padding-right: 2.75rem !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    position: relative !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1e293b !important;
    padding: 0 !important;
    line-height: normal !important;
}

.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #193f8f !important;
    box-shadow: 0 0 0 3px rgba(25, 63, 143, 0.12) !important;
    outline: none !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
    right: 8px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
}

/* Select2 Clear Button */
.select2-container--default .select2-selection--single .select2-selection__clear {
    position: absolute !important;
    right: 26px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    width: 20px !important;
    height: 20px !important;
    line-height: 18px !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #94a3b8 !important;
    background: transparent !important;
    border: none !important;
    border-radius: 50% !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    margin: 0 !important;
    transition: all 0.15s ease !important;
    z-index: 5 !important;
}

.select2-container--default .select2-selection--single .select2-selection__clear:hover {
    color: #e11d48 !important;
    background-color: #fee2e2 !important;
}

.dark .select2-container--default .select2-selection--single .select2-selection__clear,
[data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__clear {
    color: #94a3b8 !important;
}

.dark .select2-container--default .select2-selection--single .select2-selection__clear:hover,
[data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__clear:hover {
    color: #f87171 !important;
    background-color: #334155 !important;
}

.select2-dropdown {
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    overflow: hidden !important;
    z-index: 1060 !important;
}

.select2-results__option {
    font-size: 0.84rem !important;
    padding: 7px 12px !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #193f8f !important;
    color: #ffffff !important;
}

.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #edf3fc !important;
    color: #193f8f !important;
    font-weight: 600 !important;
}

/* Modern Checkbox */
.enterprise-form .form-check-input,
.enterprise-card .form-check-input {
    width: 18px !important;
    height: 18px !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 4px !important;
    cursor: pointer !important;
    transition: all 0.15s ease !important;
}

.enterprise-form .form-check-input:checked,
.enterprise-card .form-check-input:checked {
    background-color: #193f8f !important;
    border-color: #193f8f !important;
}

.enterprise-form .form-check-label,
.enterprise-card .form-check-label {
    font-size: 0.85rem !important;
    font-weight: 500 !important;
    color: #334155 !important;
    cursor: pointer !important;
}

/* ─────────────────────────────────────────────────────────────
   ULTRA-SMOOTH MODERN DATATABLE DESIGN SYSTEM
   ───────────────────────────────────────────────────────────── */

/* DataTable Outer Canvas & Container */
.datatable-canvas {
    border: 1px solid #eef2f6;
    border-radius: 10px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    transition: box-shadow 0.2s ease;
}

.dataTables_wrapper {
    position: relative;
    clear: both;
}

.dataTables_wrapper.no-footer .dataTables_scrollBody {
    border-bottom: 1px solid #edf2f7;
}

/* Modern Datatable Toolbar Header */
.datatable-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding: 0.7rem 1rem !important;
    background: #fafbfc;
    border-bottom: 1px solid #edf2f7 !important;
}

.datatable-header .dt-buttons {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
}

.datatable-header .dt-buttons .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    height: 32px;
    padding: 0 0.75rem;
    font-size: 0.785rem;
    font-weight: 500;
    color: #475569;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
}

.datatable-header .dt-buttons .btn:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
    color: #193f8f;
    box-shadow: 0 3px 6px rgba(15, 23, 42, 0.06);
    transform: translateY(-1px);
}

.datatable-header .dt-buttons .btn:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
}

/* Specific export icon colors */
.datatable-header .dt-buttons .buttons-csv i { color: #0284c7; }
.datatable-header .dt-buttons .buttons-excel i { color: #10b981; }
.datatable-header .dt-buttons .buttons-print i { color: #64748b; }
.datatable-header .dt-buttons .buttons-pdf i { color: #ef4444; }

/* Filter length / search inputs in datatable header if enabled */
.dataTables_length select {
    padding: 0.3rem 1.8rem 0.3rem 0.6rem;
    font-size: 0.8125rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #475569;
    background-color: #ffffff;
}

.dataTables_filter input {
    padding: 0.35rem 0.75rem;
    font-size: 0.8125rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #334155;
    background-color: #ffffff;
    outline: none;
    transition: all 0.15s ease;
}

.dataTables_filter input:focus {
    border-color: #193f8f;
    box-shadow: 0 0 0 3px rgba(25, 63, 143, 0.12);
}

/* ─── DataTable Table Structure ──────────────────────────────── */
.datatables,
table.dataTable {
    width: 100% !important;
    margin: 0 !important;
    border-collapse: collapse !important;
}

/* Table Header */
.datatables thead th,
.dataTables_scrollHead thead th {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-weight: 600 !important;
    font-size: 0.72rem !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1.5px solid #e2e8f0 !important;
    padding: 0.65rem 0.85rem !important;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    transition: color 0.15s ease;
}

/* Header sort active/hover - Keep uniform background, do NOT show blocky blue background! */
.datatables thead th.sorting:hover,
.dataTables_scrollHead thead th.sorting:hover,
.datatables thead th.sorting_asc,
.datatables thead th.sorting_desc,
.dataTables_scrollHead thead th.sorting_asc,
.dataTables_scrollHead thead th.sorting_desc {
    color: #193f8f !important;
    background-color: #f8fafc !important;
}

/* Table Body Cells */
.datatables tbody td,
.dataTables_scrollBody tbody td {
    padding: 0.65rem 0.85rem !important;
    font-size: 0.835rem !important;
    color: #334155;
    line-height: 1.45;
    vertical-align: middle !important;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1px solid #f1f5f9 !important;
    transition: background-color 0.15s ease;
}

/* Ultra-Smooth Row Hover Transition */
.datatables tbody tr {
    transition: background-color 0.15s ease;
}

.datatables tbody tr:hover td,
.dataTables_scrollBody tbody tr:hover td {
    background-color: #f8fafc !important;
}

/* Neutralize harsh bootstrap table striping for clean look */
.datatables.table-striped > tbody > tr:nth-of-type(odd) > *,
.datatables.table-hover > tbody > tr:hover > * {
    --bs-table-accent-bg: transparent !important;
}

/* Empty State Table Row */
.datatables td.dataTables_empty {
    padding: 2.5rem 1rem !important;
    text-align: center !important;
    color: #94a3b8 !important;
    font-size: 0.88rem !important;
    font-weight: 500 !important;
    background-color: #fafbfc !important;
}

/* ─── Fixed Columns (Left Keys & Right Action Column) ────────── */
.datatables th.dtfc-fixed-left,
.dataTables_scrollHead th.dtfc-fixed-left {
    background-color: #f8fafc !important;
    border-right: 1px solid #edf2f7 !important;
}

.datatables td.dtfc-fixed-left,
.dataTables_scrollBody td.dtfc-fixed-left {
    background-color: #ffffff !important;
    border-right: 1px solid #edf2f7 !important;
}

.datatables th.dtfc-fixed-right,
.dataTables_scrollHead th.dtfc-fixed-right {
    background-color: #f8fafc !important;
    border-left: 1px solid #edf2f7 !important;
    text-align: center;
}

.datatables td.dtfc-fixed-right,
.dataTables_scrollBody td.dtfc-fixed-right {
    background-color: #ffffff !important;
    border-left: 1px solid #edf2f7 !important;
    text-align: center;
}

.datatables tbody tr:hover td.dtfc-fixed-left,
.datatables tbody tr:hover td.dtfc-fixed-right,
.dataTables_scrollBody tbody tr:hover td.dtfc-fixed-left,
.dataTables_scrollBody tbody tr:hover td.dtfc-fixed-right {
    background-color: #f8fafc !important;
}

/* ─── Tactile Action Trigger Button & Menu ───────────────────── */
.datatables .dropdown > a,
.datatables .dropdown > a.text-body,
.table-action-btn {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b !important;
    font-size: 0.8rem;
    transition: all 0.15s ease;
    cursor: pointer;
    text-decoration: none;
    line-height: 1;
}

.datatables .dropdown > a:hover,
.datatables .dropdown > a.text-body:hover,
.datatables .dropdown.show > a,
.datatables .dropdown.show > a.text-body,
.table-action-btn:hover {
    background-color: #edf3fc !important;
    border-color: #c7d8f5 !important;
    color: #193f8f !important;
}

/* Sleek Action Dropdown Menus */
.datatables .dropdown-menu {
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.04) !important;
    padding: 0.35rem !important;
    min-width: 140px;
    z-index: 1070 !important;
}

.datatables .dropdown-menu .dropdown-item {
    padding: 0.45rem 0.75rem !important;
    font-size: 0.8125rem !important;
    font-weight: 500 !important;
    color: #334155;
    border-radius: 5px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.12s ease;
}

.datatables .dropdown-menu .dropdown-item:hover {
    background-color: #f1f5f9 !important;
    color: #0f172a !important;
}

.datatables .dropdown-menu .dropdown-item i {
    font-size: 0.85rem;
    width: 16px;
    text-align: center;
    color: #64748b;
}

.datatables .dropdown-menu .dropdown-item:hover i {
    color: #193f8f;
}

/* ─── DataTable Footer & Modern Pagination ───────────────────── */
.datatable-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding: 0.85rem 1rem !important;
    background-color: #fafbfc;
    border-top: 1px solid #edf2f7 !important;
}

.dataTables_info {
    color: #64748b !important;
    font-size: 0.8125rem !important;
    font-weight: 500 !important;
}

.dataTables_paginate {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    margin: 0 !important;
}

.dataTables_paginate .pagination {
    margin: 0 !important;
    gap: 0.25rem;
    display: inline-flex;
}

.dataTables_paginate .paginate_button {
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
}

.dataTables_paginate .paginate_button a,
.dataTables_paginate a.paginate_button {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 32px !important;
    height: 32px !important;
    padding: 0 0.65rem !important;
    font-size: 0.8125rem !important;
    font-weight: 600 !important;
    color: #475569 !important;
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02) !important;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
    text-decoration: none !important;
    cursor: pointer;
}

.dataTables_paginate .paginate_button a:hover,
.dataTables_paginate a.paginate_button:hover {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #193f8f !important;
    box-shadow: 0 2px 5px rgba(15, 23, 42, 0.06) !important;
    transform: translateY(-1px);
}

/* Active page button */
.dataTables_paginate .paginate_button.active a,
.dataTables_paginate .paginate_button.current,
.dataTables_paginate a.paginate_button.current {
    background: linear-gradient(135deg, #193f8f 0%, #112d69 100%) !important;
    border-color: #193f8f !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(25, 63, 143, 0.35) !important;
    font-weight: 700 !important;
}

/* Disabled prev/next */
.dataTables_paginate .paginate_button.disabled a,
.dataTables_paginate a.paginate_button.disabled {
    opacity: 0.35 !important;
    pointer-events: none !important;
    cursor: not-allowed !important;
    box-shadow: none !important;
}

/* ─── Custom Smooth Scrollbars ───────────────────────────────── */
.datatable-scroll-wrap::-webkit-scrollbar,
.table-responsive::-webkit-scrollbar,
.dataTables_scrollBody::-webkit-scrollbar {
    height: 6px;
    width: 6px;
}

.datatable-scroll-wrap::-webkit-scrollbar-track,
.table-responsive::-webkit-scrollbar-track,
.dataTables_scrollBody::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 4px;
}

.datatable-scroll-wrap::-webkit-scrollbar-thumb,
.table-responsive::-webkit-scrollbar-thumb,
.dataTables_scrollBody::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.datatable-scroll-wrap::-webkit-scrollbar-thumb:hover,
.table-responsive::-webkit-scrollbar-thumb:hover,
.dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* ─── Processing Loading Indicator ───────────────────────────── */
.dataTables_processing {
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    margin: 0 !important;
    border-radius: 12px !important;
    background: rgba(255, 255, 255, 0.92) !important;
    backdrop-filter: blur(8px) !important;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.1) !important;
    border: 1px solid #e2e8f0 !important;
    padding: 0.85rem 1.75rem !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    color: #193f8f !important;
    z-index: 100 !important;
}

/* ─── Soft Status Badges in Datatables ───────────────────────── */
.datatables .badge {
    font-size: 0.73rem !important;
    font-weight: 600 !important;
    padding: 0.32em 0.7em !important;
    border-radius: 6px !important;
    letter-spacing: 0.2px;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.datatables .badge.bg-success,
.datatables .badge.badge-success {
    background-color: #ecfdf5 !important;
    color: #059669 !important;
    border: 1px solid #a7f3d0 !important;
}

.datatables .badge.bg-danger,
.datatables .badge.badge-danger {
    background-color: #fef2f2 !important;
    color: #dc2626 !important;
    border: 1px solid #fecaca !important;
}

.datatables .badge.bg-warning,
.datatables .badge.badge-warning {
    background-color: #fffbeb !important;
    color: #d97706 !important;
    border: 1px solid #fde68a !important;
}

.datatables .badge.bg-info,
.datatables .badge.badge-info {
    background-color: #eff6ff !important;
    color: #2563eb !important;
    border: 1px solid #bfdbfe !important;
}

.datatables .badge.bg-secondary,
.datatables .badge.badge-secondary {
    background-color: #f1f5f9 !important;
    color: #475569 !important;
    border: 1px solid #e2e8f0 !important;
}

/* ─── Dark Mode Support ──────────────────────────────────────── */
.dark .datatable-canvas,
[data-theme="dark"] .datatable-canvas {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

.dark .datatable-header,
[data-theme="dark"] .datatable-header {
    background-color: #1e293b !important;
    border-bottom-color: #334155 !important;
}

.dark .datatable-header .dt-buttons .btn,
[data-theme="dark"] .datatable-header .dt-buttons .btn {
    background-color: #0f172a !important;
    border-color: #334155 !important;
    color: #cbd5e1 !important;
}

.dark .datatable-header .dt-buttons .btn:hover,
[data-theme="dark"] .datatable-header .dt-buttons .btn:hover {
    background-color: #334155 !important;
    color: #ffffff !important;
}

.dark .datatables thead th,
.dark .dataTables_scrollHead thead th,
[data-theme="dark"] .datatables thead th,
[data-theme="dark"] .dataTables_scrollHead thead th {
    background: #0f172a !important;
    color: #94a3b8 !important;
    border-bottom-color: #334155 !important;
}

.dark .datatables tbody td,
.dark .dataTables_scrollBody tbody td,
[data-theme="dark"] .datatables tbody td,
[data-theme="dark"] .dataTables_scrollBody tbody td {
    color: #e2e8f0 !important;
    border-bottom-color: #334155 !important;
}

.dark .datatables tbody tr:hover td,
.dark .dataTables_scrollBody tbody tr:hover td,
[data-theme="dark"] .datatables tbody tr:hover td,
[data-theme="dark"] .dataTables_scrollBody tbody tr:hover td {
    background-color: #283548 !important;
}

.dark .datatables th.dtfc-fixed-right,
.dark .dataTables_scrollHead th.dtfc-fixed-right,
[data-theme="dark"] .datatables th.dtfc-fixed-right,
[data-theme="dark"] .dataTables_scrollHead th.dtfc-fixed-right {
    background-color: #0f172a !important;
}

.dark .datatables td.dtfc-fixed-right,
.dark .dataTables_scrollBody td.dtfc-fixed-right,
[data-theme="dark"] .datatables td.dtfc-fixed-right,
[data-theme="dark"] .dataTables_scrollBody td.dtfc-fixed-right {
    background-color: #1e293b !important;
}

.dark .datatables tbody tr:hover td.dtfc-fixed-right,
.dark .dataTables_scrollBody tbody tr:hover td.dtfc-fixed-right,
[data-theme="dark"] .datatables tbody tr:hover td.dtfc-fixed-right,
[data-theme="dark"] .dataTables_scrollBody tbody tr:hover td.dtfc-fixed-right {
    background-color: #283548 !important;
}

.dark .datatables .dropdown > a,
.dark .datatables .dropdown > a.text-body,
.dark .table-action-btn,
[data-theme="dark"] .datatables .dropdown > a,
[data-theme="dark"] .datatables .dropdown > a.text-body,
[data-theme="dark"] .table-action-btn {
    background-color: #0f172a !important;
    border-color: #334155 !important;
    color: #94a3b8 !important;
}

.dark .datatables .dropdown > a:hover,
.dark .datatables .dropdown > a.text-body:hover,
.dark .table-action-btn:hover,
[data-theme="dark"] .datatables .dropdown > a:hover,
[data-theme="dark"] .datatables .dropdown > a.text-body:hover,
[data-theme="dark"] .table-action-btn:hover {
    background-color: #193f8f !important;
    border-color: #193f8f !important;
    color: #ffffff !important;
}

.dark .datatables .dropdown-menu,
[data-theme="dark"] .datatables .dropdown-menu {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

.dark .datatables .dropdown-menu .dropdown-item,
[data-theme="dark"] .datatables .dropdown-menu .dropdown-item {
    color: #cbd5e1 !important;
}

.dark .datatables .dropdown-menu .dropdown-item:hover,
[data-theme="dark"] .datatables .dropdown-menu .dropdown-item:hover {
    background-color: #334155 !important;
    color: #ffffff !important;
}

.dark .datatable-footer,
[data-theme="dark"] .datatable-footer {
    background-color: #1e293b !important;
    border-top-color: #334155 !important;
}

.dark .dataTables_info,
[data-theme="dark"] .dataTables_info {
    color: #94a3b8 !important;
}

.dark .dataTables_paginate .paginate_button a,
.dark .dataTables_paginate a.paginate_button,
[data-theme="dark"] .dataTables_paginate .paginate_button a,
[data-theme="dark"] .dataTables_paginate a.paginate_button {
    background-color: #0f172a !important;
    border-color: #334155 !important;
    color: #cbd5e1 !important;
}

.dark .dataTables_paginate .paginate_button a:hover,
.dark .dataTables_paginate a.paginate_button:hover,
[data-theme="dark"] .dataTables_paginate .paginate_button a:hover,
[data-theme="dark"] .dataTables_paginate a.paginate_button:hover {
    background-color: #334155 !important;
    border-color: #475569 !important;
    color: #ffffff !important;
}

.dark .dataTables_processing,
[data-theme="dark"] .dataTables_processing {
    background: rgba(15, 23, 42, 0.92) !important;
    border-color: #334155 !important;
    color: #60a5fa !important;
}

.dark .datatable-scroll-wrap::-webkit-scrollbar-track,
.dark .table-responsive::-webkit-scrollbar-track,
.dark .dataTables_scrollBody::-webkit-scrollbar-track,
[data-theme="dark"] .datatable-scroll-wrap::-webkit-scrollbar-track,
[data-theme="dark"] .table-responsive::-webkit-scrollbar-track,
[data-theme="dark"] .dataTables_scrollBody::-webkit-scrollbar-track {
    background: #1e293b;
}

.dark .datatable-scroll-wrap::-webkit-scrollbar-thumb,
.dark .table-responsive::-webkit-scrollbar-thumb,
.dark .dataTables_scrollBody::-webkit-scrollbar-thumb,
[data-theme="dark"] .datatable-scroll-wrap::-webkit-scrollbar-thumb,
[data-theme="dark"] .table-responsive::-webkit-scrollbar-thumb,
[data-theme="dark"] .dataTables_scrollBody::-webkit-scrollbar-thumb {
    background: #475569;
}

/* Dark Mode Filter Overrides */
.dark #enterprise-filter-panel,
[data-theme="dark"] #enterprise-filter-panel {
    background-color: #141f32 !important;
    border-bottom-color: #334155 !important;
}

.dark #filter-container .form-label,
.dark #enterprise-filter-panel .form-label,
[data-theme="dark"] #filter-container .form-label,
[data-theme="dark"] #enterprise-filter-panel .form-label {
    color: #94a3b8 !important;
}

.dark #filter-container .form-control,
.dark #filter-container .form-select,
.dark #enterprise-filter-panel .form-control,
.dark #enterprise-filter-panel .form-select,
[data-theme="dark"] #filter-container .form-control,
[data-theme="dark"] #filter-container .form-select,
[data-theme="dark"] #enterprise-filter-panel .form-control,
[data-theme="dark"] #enterprise-filter-panel .form-select {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #f1f5f9 !important;
}

.dark #filter-container .form-control:focus,
.dark #filter-container .form-select:focus,
.dark #enterprise-filter-panel .form-control:focus,
.dark #enterprise-filter-panel .form-select:focus,
[data-theme="dark"] #filter-container .form-control:focus,
[data-theme="dark"] #filter-container .form-select:focus,
[data-theme="dark"] #enterprise-filter-panel .form-control:focus,
[data-theme="dark"] #enterprise-filter-panel .form-select:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
}

.dark #filter-container .btn-group > button.multiselect,
.dark #enterprise-filter-panel .btn-group > button.multiselect,
[data-theme="dark"] #filter-container .btn-group > button.multiselect,
[data-theme="dark"] #enterprise-filter-panel .btn-group > button.multiselect {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #f1f5f9 !important;
}

.dark .multiselect-container,
[data-theme="dark"] .multiselect-container {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

.dark .multiselect-container > li > a,
[data-theme="dark"] .multiselect-container > li > a {
    color: #cbd5e1 !important;
}

.dark .multiselect-container > li > a:hover,
.dark .multiselect-container > li.active > a,
[data-theme="dark"] .multiselect-container > li > a:hover,
[data-theme="dark"] .multiselect-container > li.active > a {
    background-color: #334155 !important;
    color: #ffffff !important;
}

.dark .filter-actions-bar,
[data-theme="dark"] .filter-actions-bar {
    border-top-color: #334155 !important;
}

.dark .btn-filter-reset,
[data-theme="dark"] .btn-filter-reset {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #cbd5e1 !important;
}

.dark .btn-filter-reset:hover,
[data-theme="dark"] .btn-filter-reset:hover {
    background-color: #334155 !important;
    color: #ffffff !important;
}

.dark .btn-filter-apply,
[data-theme="dark"] .btn-filter-apply {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    border-color: #2563eb !important;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35) !important;
}

.dark .btn-filter-apply:hover,
[data-theme="dark"] .btn-filter-apply:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%) !important;
    border-color: #1d4ed8 !important;
}

/* Dark Mode Enterprise Form Overrides */
.dark .enterprise-form-card,
[data-theme="dark"] .enterprise-form-card {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25) !important;
}

.dark .enterprise-form-card .card-header,
[data-theme="dark"] .enterprise-form-card .card-header {
    background-color: #1e293b !important;
    border-bottom-color: #334155 !important;
}

.dark .enterprise-form-card .card-footer,
[data-theme="dark"] .enterprise-form-card .card-footer {
    background-color: #172033 !important;
    border-top-color: #334155 !important;
}

.dark .enterprise-form-hint,
[data-theme="dark"] .enterprise-form-hint {
    background-color: #172033 !important;
    border-color: #334155 !important;
    color: #94a3b8 !important;
}

.dark .enterprise-form .form-control,
.dark .enterprise-form .form-select,
.dark .enterprise-card .form-control,
.dark .enterprise-card .form-select,
[data-theme="dark"] .enterprise-form .form-control,
[data-theme="dark"] .enterprise-form .form-select,
[data-theme="dark"] .enterprise-card .form-control,
[data-theme="dark"] .enterprise-card .form-select {
    background-color: #0f172a !important;
    border-color: #334155 !important;
    color: #f1f5f9 !important;
}

.dark .enterprise-form .select2-container--default .select2-selection--single,
.dark .enterprise-card .select2-container--default .select2-selection--single,
.dark .select2-container--default .select2-selection--single,
[data-theme="dark"] .enterprise-form .select2-container--default .select2-selection--single,
[data-theme="dark"] .enterprise-card .select2-container--default .select2-selection--single,
[data-theme="dark"] .select2-container--default .select2-selection--single {
    background-color: #0f172a !important;
    border-color: #334155 !important;
}

.dark .select2-container--default .select2-selection--single .select2-selection__rendered,
[data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #f1f5f9 !important;
}

.dark .select2-dropdown,
[data-theme="dark"] .select2-dropdown {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

.dark .select2-results__option,
[data-theme="dark"] .select2-results__option {
    color: #cbd5e1 !important;
}

.dark .select2-container--default .select2-results__option--highlighted[aria-selected],
[data-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #193f8f !important;
    color: #ffffff !important;
}

</style>

{{-- Pickadate Theme CSS from beltei_ums --}}
<link rel="stylesheet" href="{{ asset('assets/extend/pickadate/themes/pickadate-limitless.css') }}?v={{ config('init.layout_version') }}">
