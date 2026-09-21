<link href="https://fonts.googleapis.com/css2?family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Hanuman:wght@400;700&display=swap" rel="stylesheet">
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
.sidebar-main {
    background-color: #ffffff !important;
    border-right: 1px solid #eef2f6 !important;
    box-shadow: none !important;
    transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
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

/* Proportional Lucide Icons in Navigation (Not oversized!) */
.sidebar-main .nav-sidebar .nav-link .nav-link-icon,
.sidebar-main .nav-sidebar .nav-link svg.lucide,
.sidebar-main .nav-sidebar .nav-link [data-lucide] {
    width: 15px !important;
    height: 15px !important;
    max-width: 15px !important;
    max-height: 15px !important;
    margin-right: 0.75rem !important;
    stroke: #475569;
    stroke-width: 1.6 !important;
    flex-shrink: 0;
    vertical-align: middle;
    display: inline-block;
    transition: stroke 0.15s ease;
}

.sidebar-main .nav-sidebar .nav-link:hover .nav-link-icon,
.sidebar-main .nav-sidebar .nav-link.active .nav-link-icon,
.sidebar-main .nav-sidebar .nav-item.nav-item-open > .nav-link .nav-link-icon {
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
    overflow: hidden;
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

.enterprise-filter-canvas {
    background-color: #fcfdfe;
}

/* Unified Form Controls */
.form-control,
.form-select {
    border-color: #e2e8f0;
    border-radius: 6px;
    font-size: 0.84rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #193f8f !important;
    box-shadow: 0 0 0 3px rgba(25, 63, 143, 0.12) !important;
}

.form-label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #334155;
    margin-bottom: 0.3rem;
}

.form-label.required::after,
.required::after {
    content: " *";
    color: #e11d48;
    font-weight: bold;
}

/* Modern Datatable Toolbar & Elements */
.datatable-header {
    padding: 0.5rem 0.75rem !important;
    border-bottom: 1px solid #f1f5f9;
}

.datatable-footer {
    padding: 0.65rem 0.75rem !important;
    border-top: 1px solid #f1f5f9;
}

.datatables thead th {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-weight: 600 !important;
    font-size: 0.78rem !important;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    border-bottom: 1.5px solid #e2e8f0 !important;
    padding: 0.6rem 0.75rem !important;
}

.datatables tbody td {
    padding: 0.55rem 0.75rem !important;
    font-size: 0.83rem !important;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.datatables tbody tr:hover td {
    background-color: #f8fafd !important;
}

/* Action button tooltips in datatables */
.table-action-btn {
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    padding: 0;
    font-size: 13px;
    transition: all 0.15s ease;
}

</style>

{{-- Pickadate Theme CSS from beltei_ums --}}
<link rel="stylesheet" href="{{ asset('assets/extend/pickadate/themes/pickadate-limitless.css') }}?v={{ config('init.layout_version') }}">
