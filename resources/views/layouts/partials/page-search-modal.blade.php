{{-- Modern Spotlight / Command Palette Modal (Ctrl + K) --}}
@php
    $searchablePages = collect();

    if (isset($menus) && is_iterable($menus)) {
        foreach ($menus as $m) {
            $parentName = $m->name ?? '';
            $parentIcon = get_lucide_icon($m->icon ?? 'folder');

            if ($m->route && Route::has($m->route)) {
                $searchablePages->push([
                    'title' => $parentName,
                    'category' => 'Navigation',
                    'route' => route($m->route),
                    'icon' => $parentIcon,
                    'keywords' => strtolower($parentName)
                ]);
            }

            if (!empty($m->children) && is_iterable($m->children)) {
                foreach ($m->children as $c) {
                    if ($c->route && Route::has($c->route)) {
                        $searchablePages->push([
                            'title' => $c->name ?? '',
                            'category' => $parentName,
                            'route' => route($c->route),
                            'icon' => get_lucide_icon($c->icon ?? $parentIcon),
                            'keywords' => strtolower(($c->name ?? '') . ' ' . $parentName)
                        ]);
                    }
                }
            }
        }
    }
@endphp

<div class="modal fade" id="pageSearchModal" tabindex="-1" aria-labelledby="pageSearchModalLabel" aria-hidden="true" style="backdrop-filter: blur(6px); background: rgba(15, 23, 42, 0.45);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 620px;">
        <div class="modal-content border-0 shadow-24" style="border-radius: 14px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            
            <!-- Search Header -->
            <div class="modal-header border-bottom p-3 bg-white d-flex align-items-center">
                <div class="input-group input-group-lg border-0 shadow-none align-items-center">
                    <span class="input-group-text bg-transparent border-0 pe-2 text-muted" style="font-size: 1.1rem;">
                        <i data-lucide="search" style="width: 20px; height: 20px;"></i>
                    </span>
                    <input type="text" id="globalPageSearchInput" class="form-control border-0 bg-transparent shadow-none" 
                           placeholder="{{ __('Search pages, reports, features... (e.g. FPCS, Transfer, Room, Invoice)') }}" 
                           style="font-size: 0.95rem;" autocomplete="off">
                    <button type="button" class="btn btn-sm btn-light border text-muted px-2 py-0 rounded" data-bs-dismiss="modal" style="font-size: 0.72rem; line-height: 1.6;">
                        <kbd class="font-monospace text-muted" style="background: transparent; border: none; font-size: 0.7rem;">ESC</kbd>
                    </button>
                </div>
            </div>

            <!-- Quick Suggestions Header (when query is empty) -->
            <div id="pageSearchSuggestionsBar" class="px-3 pt-2 pb-1 bg-light border-bottom d-flex align-items-center gap-1 flex-wrap" style="font-size: 0.75rem;">
                <span class="text-muted me-1 fw-semibold">{{ __('Popular:') }}</span>
                <span class="badge bg-white text-dark border quick-tag" style="cursor: pointer;" onclick="quickSearch('Staying')">Staying Guests</span>
                <span class="badge bg-white text-dark border quick-tag" style="cursor: pointer;" onclick="quickSearch('FPCS')">FPCS Police</span>
                <span class="badge bg-white text-dark border quick-tag" style="cursor: pointer;" onclick="quickSearch('Transfer')">SAI Transfers</span>
                <span class="badge bg-white text-dark border quick-tag" style="cursor: pointer;" onclick="quickSearch('Invoice')">Invoices</span>
                <span class="badge bg-white text-dark border quick-tag" style="cursor: pointer;" onclick="quickSearch('Tour')">Tours Catalog</span>
            </div>

            <!-- Search Results Body -->
            <div class="modal-body p-2" style="max-height: 420px; overflow-y: auto;" id="pageSearchResultsContainer">
                <div id="pageSearchResultsList" class="list-group list-group-flush">
                    <!-- Results dynamically injected via JS -->
                </div>

                <!-- Empty State -->
                <div id="pageSearchEmptyState" class="text-center py-5 text-muted d-none">
                    <i data-lucide="search-x" style="width: 48px; height: 48px;" class="mb-3 text-secondary opacity-50"></i>
                    <h6 class="fw-semibold text-dark">{{ __('No matching pages found') }}</h6>
                    <p class="small mb-0 text-muted">{{ __('Try searching for "staying", "fpcs", "transfer", "report", or "invoice"') }}</p>
                </div>
            </div>

            <!-- Search Footer / Keyboard Shortcuts -->
            <div class="modal-footer bg-light py-2 px-3 border-top d-flex justify-content-between align-items-center text-muted small" style="font-size: 0.75rem;">
                <div class="d-flex align-items-center gap-3">
                    <span><kbd class="bg-white border text-dark px-1 py-0 rounded" style="font-size: 0.68rem;">↑</kbd> <kbd class="bg-white border text-dark px-1 py-0 rounded" style="font-size: 0.68rem;">↓</kbd> navigate</span>
                    <span><kbd class="bg-white border text-dark px-1 py-0 rounded" style="font-size: 0.68rem;">↵</kbd> select</span>
                    <span><kbd class="bg-white border text-dark px-1 py-0 rounded" style="font-size: 0.68rem;">ESC</kbd> close</span>
                </div>
                <div class="text-end">
                    <span class="text-muted" style="font-size: 0.72rem;">{{ count($searchablePages) }} pages indexed</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .quick-tag:hover {
        background-color: #0d6efd !important;
        color: #fff !important;
        border-color: #0d6efd !important;
    }
    .page-search-item {
        border-radius: 8px !important;
        margin-bottom: 2px;
        padding: 8px 12px;
        border: 1px solid transparent !important;
        transition: all 0.12s ease;
        cursor: pointer;
        background: transparent;
    }
    .page-search-item:hover, .page-search-item.active-item {
        background-color: #f1f7ff !important;
        border-color: #cbe2fe !important;
    }
    .page-search-item.active-item .page-title {
        color: #0d6efd !important;
        font-weight: 600;
    }
    .page-search-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        font-size: 0.9rem;
    }
</style>

@push('scripts')
<script>
(function() {
    const pagesIndex = @json($searchablePages);
    let selectedIndex = 0;
    let filteredList = [];

    // Keyboard shortcut: Ctrl + K or Cmd + K
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            $('#pageSearchModal').modal('toggle');
        }
    });

    // When modal opens, autofocus search input and show default list
    $('#pageSearchModal').on('shown.bs.modal', function() {
        $('#globalPageSearchInput').val('').focus();
        renderResults('');
    });

    // Listen to typing
    $('#globalPageSearchInput').on('input', function() {
        renderResults($(this).val().trim());
    });

    // Keyboard navigation (Up/Down/Enter)
    $('#globalPageSearchInput').on('keydown', function(e) {
        const items = $('#pageSearchResultsList .page-search-item');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (filteredList[selectedIndex]) {
                window.location.href = filteredList[selectedIndex].route;
            }
        }
    });

    function updateActiveItem(items) {
        items.removeClass('active-item');
        const activeElem = items.eq(selectedIndex);
        activeElem.addClass('active-item');
        if (activeElem.length) {
            activeElem[0].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    }

    window.quickSearch = function(tag) {
        $('#globalPageSearchInput').val(tag).trigger('input').focus();
    };

    function renderResults(query) {
        const q = query.toLowerCase();
        selectedIndex = 0;

        if (!q) {
            filteredList = pagesIndex; // Show all by default
        } else {
            filteredList = pagesIndex.filter(item => {
                return item.title.toLowerCase().includes(q) || 
                       item.category.toLowerCase().includes(q) || 
                       item.keywords.includes(q);
            });
        }

        const listContainer = $('#pageSearchResultsList');
        listContainer.empty();

        if (filteredList.length === 0) {
            $('#pageSearchEmptyState').removeClass('d-none');
            return;
        }

        $('#pageSearchEmptyState').addClass('d-none');

        filteredList.forEach((item, idx) => {
            const activeClass = idx === 0 ? 'active-item' : '';
            const html = `
                <a href="${item.route}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between page-search-item ${activeClass}" data-index="${idx}">
                    <div class="d-flex align-items-center">
                        <div class="page-search-icon bg-primary bg-opacity-10 text-primary me-2">
                            <i data-lucide="${item.icon}" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div>
                            <div class="page-title text-dark fw-medium" style="font-size: 0.88rem;">${item.title}</div>
                            <small class="text-muted" style="font-size: 0.75rem;"><i data-lucide="folder" class="me-1 opacity-75" style="width: 12px; height: 12px;"></i>${item.category}</small>
                        </div>
                    </div>
                    <div class="text-muted small d-flex align-items-center">
                        <span class="badge bg-light text-secondary border me-1 font-monospace" style="font-size: 0.68rem;">Jump ↵</span>
                    </div>
                </a>
            `;
            listContainer.append(html);
        });

        if (window.lucide) {
            lucide.createIcons({ root: listContainer[0] });
        }
    }
})();
</script>
@endpush
