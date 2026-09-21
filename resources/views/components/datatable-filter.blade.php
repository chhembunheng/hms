

@props(['filters' => [], 'title' => __('global.filters')])

<div id="filter-container" class="card border border-primary shadow-sm mb-3">
    <div class="card-header bg-primary text-white border-bottom-0 py-2 d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fa-solid fa-filter me-2"></i>{{ $title }}
        </h6>
        <button type="button" class="btn btn-sm btn-light" id="toggle-filters" title="{{ __('global.toggle_filters') }}">
            <i class="fa-solid fa-chevron-up"></i>
        </button>
    </div>
    {{-- filter-body starts invisible to prevent raw-scrollbox FOUC --}}
    <div class="card-body" id="filter-body" style="opacity:0; visibility:hidden; transition: opacity 0.15s ease;">
        <div class="row g-3">
            {{ $slot }}
        </div>
        <div class="d-flex gap-2 mt-3 pt-3 border-top">
            <button type="button" class="btn btn-primary btn-sm" id="apply-filters">
                <i class="fa-solid fa-check me-1"></i>{{ __('global.apply') }}
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="reset-filters">
                <i class="fa-solid fa-rotate-left me-1"></i>{{ __('global.reset') }}
            </button>
        </div>
    </div>
</div>


@push('scripts')
<script>
(function () {

    const FILTER_CONTAINER = '#filter-container';
    const FILTER_BODY      = '#filter-body';

    /* ─── Bootstrap Multiselect init ─────────────────────────── */
    function initBootstrapMultiselect() {

        let initCount  = 0;
        let doneCount  = 0;

        const $selects = $(FILTER_CONTAINER).find('select.multiple-select');
        initCount = $selects.length;

        if (initCount === 0) {
            revealFilter(); // nothing to init – show immediately
            return;
        }

        $selects.each(function () {

            const $select  = $(this);
            const server   = $select.data('server');
            const filters  = ($select.data('filters') || '').split(',');
            const isMulti  = $select.prop('multiple');
            const optCount = $select.find('option').length;

            if ($select.data('ms-initialized')) {
                doneCount++;
                if (doneCount === initCount) revealFilter();
                return;
            }
            $select.data('ms-initialized', true);

            $select.multiselect({
                includeSelectAllOption: isMulti && (!!server || optCount > 2),
                enableFiltering:        !!server || optCount > 5,
                enableCaseInsensitiveFiltering: true,
                buttonWidth:  '100%',
                buttonClass:  'btn btn-light border form-select-sm text-start w-100',
                maxHeight:    300,
                numberDisplayed: isMulti ? 1 : 999,
                nonSelectedText:  '{{ __("global.all") }}',
                selectAllText:    '{{ __("global.select_all") }}',
                allSelectedText:  '{{ __("global.all_selected") }}',
                nSelectedText:    '{{ __("global.selected") }}',
            });

            // AJAX-loaded selects: fetch options on first open
            if (server) {
                $select.parent()
                    .find('button.multiselect')
                    .one('click', function () {
                        const payload = {};
                        filters.forEach(f => {
                            payload[f] = $(`[name="${f}[]"]`).val() || $(`[name="${f}"]`).val();
                        });
                        $.ajax({
                            url: server, type: 'GET', data: payload, dataType: 'json',
                            success: function (res) {
                                if (!res || !res.results) return;
                                $select.empty();
                                res.results.forEach(item => {
                                    $select.append(`<option value="${item.id}">${item.text}</option>`);
                                });
                                $select.multiselect('rebuild');
                            }
                        });
                    });
            }

            doneCount++;
            if (doneCount === initCount) revealFilter();
        });
    }

    /* Fade-in the filter body after init – no jarring rebuild flash */
    function revealFilter() {
        const $body = $(FILTER_BODY);
        $body.css({ visibility: 'visible', opacity: 1 });
    }

    /* ─── DataTables helpers ──────────────────────────────────── */
    function reloadDatatables(callback) {
        $('.dataTable').each(function () {
            const tbl = $(this).DataTable();
            if (tbl) tbl.ajax.reload(callback, false);
        });
    }

    /* ─── Page actions ────────────────────────────────────────── */
    $(document).ready(function () {

        initBootstrapMultiselect();

        // Toggle filter panel
        $('#toggle-filters').on('click', function () {
            $(FILTER_BODY).slideToggle(200);
            $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
        });

        // Apply
        $('#apply-filters').on('click', function () {
            const $btn = $(this);
            const html = $btn.html();
            $btn.prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin me-1"></i>{{ __("global.applying") }}');
            reloadDatatables(() => {
                $btn.prop('disabled', false).html(html);
            });
        });

        // Reset
        $('#reset-filters').on('click', function () {
            const $c = $(FILTER_CONTAINER);
            $c.find('input, textarea').val('');
            $c.find('select.multiple-select').each(function () {
                $(this).val([]);
                $(this).multiselect('rebuild');
            });
            // Also clear plain selects
            $c.find('select:not(.multiple-select)').each(function () {
                $(this).prop('selectedIndex', 0);
            });
            $('#apply-filters').trigger('click');
        });

        // Enter key = Apply
        $(FILTER_CONTAINER).on('keydown', 'input, select, textarea', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#apply-filters').trigger('click');
            }
        });
    });

})();
</script>
@endpush
