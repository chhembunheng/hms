

@props(['filters' => [], 'title' => __('global.filters'), 'seamless' => false])

@if($seamless)
<div id="filter-container" class="enterprise-filter-canvas mb-0">
    <div id="filter-body">
        <div class="row g-3">
            {{ $slot }}
        </div>
        <div class="d-flex justify-content-end gap-2 mt-3 pt-2 border-top">
            <button type="button" class="btn btn-secondary btn-sm d-inline-flex align-items-center gap-1 rounded-2" id="reset-filters">
                <i data-lucide="rotate-cw" style="width: 13px; height: 13px;"></i>
                <span>{{ __('global.reset') }}</span>
            </button>
            <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 rounded-2 px-3" id="apply-filters">
                <i data-lucide="check" style="width: 13px; height: 13px;"></i>
                <span>{{ __('global.apply') }}</span>
            </button>
        </div>
    </div>
</div>
@else
<div id="filter-container" class="card border border-primary shadow-sm mb-3">
    <div class="card-header bg-primary text-white border-bottom-0 py-2 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 d-flex align-items-center gap-2">
            <i data-lucide="filter" style="width: 15px; height: 15px;"></i>
            <span>{{ $title }}</span>
        </h6>
        <button type="button" class="btn btn-sm btn-light" id="toggle-filters" title="{{ __('global.toggle_filters') }}">
            <i data-lucide="chevron-up" style="width: 14px; height: 14px;"></i>
        </button>
    </div>
    <div class="card-body" id="filter-body">
        <div class="row g-3">
            {{ $slot }}
        </div>
        <div class="d-flex gap-2 mt-3 pt-3 border-top">
            <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 rounded-2" id="apply-filters">
                <i data-lucide="check" style="width: 13px; height: 13px;"></i>
                <span>{{ __('global.apply') }}</span>
            </button>
            <button type="button" class="btn btn-secondary btn-sm d-inline-flex align-items-center gap-1 rounded-2" id="reset-filters">
                <i data-lucide="rotate-cw" style="width: 13px; height: 13px;"></i>
                <span>{{ __('global.reset') }}</span>
            </button>
        </div>
    </div>
</div>
@endif


@push('scripts')
<script>
(function () {

    const FILTER_CONTAINER = '#filter-container';
    const FILTER_BODY      = '#filter-body';

    /* ─── Bootstrap Multiselect init ─────────────────────────── */
    function initBootstrapMultiselect() {
        $(FILTER_CONTAINER).find('select.multiple-select').each(function () {
            const $select  = $(this);
            if ($select.data('ms-initialized') || $select.parent().hasClass('multiselect-native-select')) {
                return;
            }
            $select.data('ms-initialized', true);

            const server   = $select.data('server');
            const filters  = ($select.data('filters') || '').split(',');
            const isMulti  = $select.prop('multiple');
            const optCount = $select.find('option').length;

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
        });
    }

    /* ─── DataTables helpers (matching beltei_ums) ──────────── */
    function reloadDatatables(callback) {
        if ($('body').find('.dataTables_wrapper').length) {
            $('body').find('.dataTables_wrapper table').each(function () {
                const tbl = $(this).DataTable();
                if (tbl) tbl.ajax.reload(callback, false);
            });
        } else {
            $('.dataTable').each(function () {
                const tbl = $(this).DataTable();
                if (tbl) tbl.ajax.reload(callback, false);
            });
        }
    }

    /* ─── Page actions ────────────────────────────────────────── */
    $(document).ready(function () {

        initBootstrapMultiselect();

        // Toggle filter panel
        $('#toggle-filters').on('click', function () {
            $(FILTER_BODY).slideToggle(200);
            $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
        });

        // Apply / Filter button (supporting both #apply-filters and #filter-btn)
        $('#apply-filters, #filter-btn').on('click', function () {
            const $btn = $(this);
            const html = $btn.html();
            $btn.prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin me-1"></i>{{ __("global.applying") }}');
            reloadDatatables(() => {
                $btn.prop('disabled', false).html(html);
            });
        });

        // Reset / Clear button (supporting both #reset-filters and #clear-btn)
        $('#reset-filters, #clear-btn').on('click', function () {
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
            // Also reset pickadate pickers if any
            $c.find('input.pickadate, input.pick-adate, input.datepicker').each(function() {
                const picker = $(this).pickadate('picker');
                if (picker) picker.clear();
            });
            reloadDatatables();
        });

        // Enter key = Apply
        $(FILTER_CONTAINER).on('keydown', 'input, select, textarea', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#apply-filters, #filter-btn').first().trigger('click');
            }
        });
    });

})();
</script>
@endpush
