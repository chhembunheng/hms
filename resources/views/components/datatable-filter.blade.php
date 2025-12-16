

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
    <div class="card-body" id="filter-body">
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

    function initBootstrapMultiselect() {

        $(FILTER_CONTAINER).find('select.multiple-select').each(function () {

            const $select = $(this);
            const server = $select.data('server');
            const filters = ($select.data('filters') || '').split(',');

            if ($select.data('ms-initialized')) return;

            $select.multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '100%',
                maxHeight: 300,

                nonSelectedText: 'None Selected',
                selectAllText: '{{ __("global.select_all") }}',
                allSelectedText: '{{ __("global.all_selected") }}',
                nSelectedText: '{{ __("global.selected") }}'
            });

            $select.val([]);
            $select.multiselect('refresh');

            $select.parent()
                .find('button.multiselect')
                .one('click', function () {

                    if (!server) return;

                    const payload = {};
                    filters.forEach(f => {
                        payload[f] =
                            $(`[name="${f}[]"]`).val() ||
                            $(`[name="${f}"]`).val();
                    });

                    $.ajax({
                        url: server,
                        type: 'GET',
                        data: payload,
                        dataType: 'json',
                        success: function (res) {

                            if (!res || !res.results) return;

                            $select.empty();

                            res.results.forEach(item => {
                                $select.append(
                                    `<option value="${item.id}">${item.text}</option>`
                                );
                            });

                            // Rebuild AFTER data load
                            $select.multiselect('rebuild');
                        }
                    });
                });

            $select.data('ms-initialized', true);
        });
    }


    function reloadDatatables(callback = null) {
        $('.dataTable').each(function () {
            const table = $(this).DataTable();
            if (table) table.ajax.reload(callback, false);
        });
    }


        initBootstrapMultiselect();

    /* ===============================
       PAGE ACTIONS
    =============================== */
    $(document).ready(function () {

        // Toggle filter
        $('#toggle-filters').on('click', function () {
            $('#filter-body').slideToggle(200);
            $(this).find('i')
                .toggleClass('fa-chevron-up fa-chevron-down');
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

            // Clear inputs
            $c.find('input, textarea').val('');

            // Clear multiselects
            $c.find('select.multiple-select').each(function () {
                $(this).val([]);
                $(this).multiselect('rebuild');
            });

            $('#apply-filters').trigger('click');
        });

        // Enter = Apply
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
