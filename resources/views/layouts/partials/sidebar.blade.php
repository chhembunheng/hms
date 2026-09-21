@php
    $searchItems = [];
    if (isset($menus) && is_iterable($menus)) {
        foreach ($menus as $m) {
            $parentName = $m->name ?? '';
            $parentIcon = $m->icon ?: 'fa-circle';
            if ($m->route && Route::has($m->route)) {
                $searchItems[] = [
                    'name' => $parentName,
                    'category' => '',
                    'url' => strpos($m->route, 'frontend.') === 0 ? route($m->route, ['locale' => app()->getLocale()]) : route($m->route),
                    'icon' => $parentIcon,
                ];
            }
            if (!empty($m->children) && $m->children->count()) {
                foreach ($m->children as $child) {
                    if ($child->route && Route::has($child->route)) {
                        $searchItems[] = [
                            'name' => $child->name ?? '',
                            'category' => $parentName,
                            'url' => strpos($child->route, 'frontend.') === 0 ? route($child->route, ['locale' => app()->getLocale()]) : route($child->route),
                            'icon' => $child->icon ?? $parentIcon,
                        ];
                    }
                }
            }
        }
    }
@endphp

<div class="sidebar sidebar-light sidebar-main sidebar-expand-lg border-end">
    <!-- Sidebar content -->
    <div class="sidebar-content slim-scroll d-flex flex-column h-100">
        <!-- Beltei-style Modern Search Container -->
        <div class="sidebar-search-container px-3 pt-3 pb-2">
            <div class="search-container position-relative">
                <input type="text"
                    class="form-control"
                    id="search-menu"
                    name="search"
                    placeholder="{{ __('Search pages...') }}"
                    autocomplete="off">
                <i class="fa-solid fa-magnifying-glass search-icon-wrapper"></i>
                <button type="button" id="clear-search-menu" class="btn-clear-search d-none" aria-label="Clear">
                    <i class="fa-solid fa-xmark" style="font-size: 13px;"></i>
                </button>
                <ul id="autocomplete-dropdown" class="dropdown-menu"></ul>
            </div>
        </div>

        <!-- Main navigation -->
        <div class="flex-grow-1 overflow-auto">
            <ul class="nav nav-sidebar py-1" data-nav-type="accordion">
                @if(isset($menus) && is_iterable($menus))
                    @foreach ($menus as $menu)
                        @include('layouts.partials.menu', ['menu' => $menu])
                    @endforeach
                @endif
            </ul>
        </div>

        <!-- Sidebar footer -->
        <div class="sidebar-footer text-center py-2 mt-auto border-top">
            <small class="text-muted" style="font-size: 0.73rem;">
                &copy; {{ date('Y') }} <a href="https://github.com/chhembunheng" target="_blank" class="text-decoration-none fw-semibold" style="color: #193f8f;">@hengdev_04</a><br>
            </small>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const searchItems = @json($searchItems);
    const $input = $('#search-menu');
    const $dropdown = $('#autocomplete-dropdown');
    const $clearBtn = $('#clear-search-menu');
    let selectedIndex = -1;

    // Smooth auto-scroll to active menu item (matching beltei_ums with smooth easing)
    function scrollToMenuItem($element) {
        const $sidebarScrollable = $('.sidebar-content .flex-grow-1');
        if (!$sidebarScrollable.length || !$element.length) return;

        const elementTop = $element.offset().top;
        const containerTop = $sidebarScrollable.offset().top;
        const elementHeight = $element.outerHeight();
        const containerHeight = $sidebarScrollable.outerHeight();
        const currentScroll = $sidebarScrollable.scrollTop();

        const scrollPosition = currentScroll + elementTop - containerTop - (containerHeight / 2) + (elementHeight / 2);

        $sidebarScrollable.stop().animate({
            scrollTop: scrollPosition
        }, 320, 'swing');
    }

    const $activeItem = $('.nav-sidebar .nav-link.active').first();
    if ($activeItem.length) {
        setTimeout(function() {
            scrollToMenuItem($activeItem);
        }, 150);
    }

    // Scroll to clicked item smoothly
    $('.nav-sidebar').on('click', '.nav-link', function() {
        const $clicked = $(this);
        setTimeout(function() {
            scrollToMenuItem($clicked);
        }, 120);
    });

    // Instant Client-side Search with real-time dropdown and tree filter
    $input.on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        selectedIndex = -1;

        if (!query) {
            $dropdown.hide().empty();
            $clearBtn.addClass('d-none');
            // Reset sidebar menu filter
            $('.nav-sidebar .nav-item').show();
            $('.nav-sidebar .nav-group-sub').not('.show-originally').removeClass('search-expanded');
            return;
        }

        $clearBtn.removeClass('d-none');

        // Filter matching items
        const matches = searchItems.filter(function(item) {
            return item.name.toLowerCase().includes(query) || 
                   (item.category && item.category.toLowerCase().includes(query));
        });

        if (matches.length > 0) {
            let html = '';
            matches.slice(0, 10).forEach(function(item, idx) {
                let iconClass = item.icon || 'fa-circle';
                if (!iconClass.includes('fa-solid') && !iconClass.includes('fa-regular') && !iconClass.includes('fa-brands')) {
                    iconClass = iconClass.startsWith('fa-') ? 'fa-solid ' + iconClass : 'fa-solid fa-' + iconClass;
                }
                html += `
                    <li class="dropdown-item" data-link="${item.url}">
                        <i class="${iconClass} me-2" style="width: 16px; text-align: center;"></i>
                        <div class="flex-grow-1 text-truncate">
                            <span class="item-title">${item.name}</span>
                            ${item.category ? `<small class="text-muted d-block" style="font-size: 0.7rem;">${item.category}</small>` : ''}
                        </div>
                    </li>
                `;
            });
            $dropdown.html(html).show();
        } else {
            $dropdown.html(`
                <li class="dropdown-item disabled text-muted py-2 text-center" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-circle-info me-1"></i> {{ __('No pages found') }}
                </li>
            `).show();
        }

        // Real-time smooth tree filtering
        $('.nav-sidebar > .nav-item').each(function() {
            const $item = $(this);
            const itemText = $item.find('.nav-link-title').text().toLowerCase();
            let hasMatch = itemText.includes(query);

            const $subItems = $item.find('.nav-group-sub .nav-item');
            if ($subItems.length) {
                let subMatch = false;
                $subItems.each(function() {
                    const $sub = $(this);
                    const subText = $sub.find('.nav-link-title').text().toLowerCase();
                    if (subText.includes(query)) {
                        $sub.show();
                        subMatch = true;
                    } else {
                        $sub.hide();
                    }
                });
                if (subMatch) {
                    hasMatch = true;
                    $item.find('.nav-group-sub').addClass('search-expanded').show();
                } else if (!hasMatch) {
                    $item.find('.nav-group-sub').removeClass('search-expanded').hide();
                }
            }

            $item.toggle(hasMatch);
        });
    });

    // Clear search
    $clearBtn.on('click', function() {
        $input.val('').trigger('input').focus();
    });

    // Dropdown item click
    $dropdown.on('click', '.dropdown-item:not(.disabled)', function() {
        const link = $(this).data('link');
        if (link) window.location.href = link;
    });

    // Keyboard navigation
    $input.on('keydown', function(e) {
        const items = $dropdown.find('.dropdown-item:not(.disabled)');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && items.eq(selectedIndex).length) {
                items.eq(selectedIndex).trigger('click');
            } else if (items.first().length) {
                items.first().trigger('click');
            }
            return;
        } else if (e.key === 'Escape') {
            $dropdown.hide();
            return;
        }

        items.removeClass('active');
        if (selectedIndex >= 0) {
            items.eq(selectedIndex).addClass('active');
        }
    });

    // Close dropdown on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            $dropdown.hide();
        }
    });

    // Global Ctrl+K / Cmd+K focuses sidebar search input smoothly
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            $input.focus().select();
        }
    });
});
</script>
@endpush
