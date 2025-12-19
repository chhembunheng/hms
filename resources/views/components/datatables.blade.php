@props([
    'data' => null,
    'dataTable' => null,
    'title' => null,
    'content' => false,
    'fixed' => false,
])
@php
    // Support both 'data' and 'dataTable' props for backwards compatibility
    $tableData = $dataTable ?? $data;
@endphp
@if($tableData)
<div class="card border border-primary shadow-sm">
    <div class="card-header bg-primary text-white border-bottom-0 py-2">
        <h6 class="mb-0">{{ $title }}</h6>
    </div>
    <div class="card-body">
        {!! $tableData->table(['class' => 'table table-hover datatables no-footer'], true) !!}
    </div>
</div>
@else
<div class="alert alert-warning">
    <i class="fa-solid fa-exclamation-triangle me-2"></i>
    No datatable provided. Please pass the datatable object to this component.
</div>
@endif
@push('scripts')
    <script src="{{ asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/extensions/col_reorder.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/extensions/key_table.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/extensions/select.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script>
        var _initDataTables = function() {
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                pageLength: 25,
                dom: '<"datatable-header"><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': document.dir == "rtl" ? '<i class="fa-solid fa-angle-left"></i>' : '<i class="fa-solid fa-angle-right"></i>',
                        'previous': document.dir == "rtl" ? '<i class="fa-solid fa-angle-right"></i>' : '<i class="fa-solid fa-angle-left"></i>'
                    }
                },
                scrollX: true,
                scrollY: '50vh',
                scrollCollapse: true,
                responsive: true,
                fixedHeader: {
                    header: true,
                    footer: false
                },
                searching: false,
                initComplete: function(settings, json) {
                    $(document).find('.dataTables_paginate .paginate_button a').addClass('rounded-pill');
                },
                drawCallback: function(settings) {
                    $(document).find('.dataTables_paginate .paginate_button a').addClass('rounded-pill');
                },
                ajax: {
                    beforeSend: function(xhr) {
                        let filters = {};
                        $('#filter-container').find('input, select, textarea').each(function() {
                            let $el = $(this);
                            let name = ($el.attr('name') || '').replace('[]', '');
                            let value = $el.val();

                            // Handle multiselect arrays
                            if (name && value) {
                                if (Array.isArray(value)) {
                                    // For multiselect, only add if array has values
                                    if (value.length > 0) {
                                        filters[name] = value;
                                    }
                                } else if (value !== '') {
                                    filters[name] = value;
                                }
                            }
                        });
                        xhr.setRequestHeader('filters', encodeURIComponent(JSON.stringify(filters)));
                    },
                    complete: function(xhr) {
                        let error = xhr.responseJSON?.error || '';
                        if (error) {
                            swalInit.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: error
                            });
                        }
                    }
                }
            });
        };
        @if ($fixed)
            $.extend($.fn.dataTable.defaults, {
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                }
            });
        @endif


        function format(row) {
            const content = row.content || {};
            const headers = content.headers || [];
            const data = content.data || [];
            let tr = '';
            if (data.length) {
                tr += '<table class="table table-hover table-striped table-lg">';
                tr += '<thead>';
                tr += '<tr>';
                headers.forEach(function(header) {
                    tr += '<th>' + header + '</th>';
                });
                tr += '</tr>';
                tr += '</thead>';
                tr += '<tbody>';
                data.forEach(function(row) {
                    tr += '<tr>';
                    row.forEach(function(cell) {
                        tr += '<td>' + cell + '</td>';
                    });
                    tr += '</tr>';
                });
                tr += '</tbody>';
                tr += '</table>';
            }
            return tr;
        }
        $(document).on('click', '.datatables td.content', function(e) {
            var table = $(this).closest('table').DataTable();
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
        $(document).on('shown.bs.dropdown', '.datatables .dropdown-toggle', function(e) {
            $(this).parents('.datatables').find('td.dtfc-fixed-right').not($(this).parents('td')).css('z-index', 'auto');
        });
        $(document).on('hidden.bs.dropdown', '.datatables .dropdown-toggle', function(e) {
            $(this).parents('.datatables').find('td.dtfc-fixed-right').css('z-index', '1');
        });

        // Re-initialize GLightbox after DataTable redraws
        $(document).on('draw.dt', '.datatables', function() {
            if (typeof GLightbox !== 'undefined') {
                const lightbox = GLightbox({
                    selector: '[data-bs-popup="lightbox"]',
                    loop: true,
                    svg: {
                        next: document.dir == "rtl" ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"><g><path d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z"/></g></svg>' : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"> <g><path d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z"/></g></svg>',
                        prev: document.dir == "rtl" ? '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"><g><path d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z"/></g></svg>' : '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.175 477.175" xml:space="preserve"><g><path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"/></g></svg>'
                    }
                });
            }
        });

        _initDataTables();
    </script>
    {!! $data->scripts() !!}
    <style>
        td.content {
            background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.content {
            background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
        }
        .dataTables_scrollBody {
            min-height: 550px !important;
        }
    </style>
@endpush
