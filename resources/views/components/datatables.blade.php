@props([
    'data' => null,
    'dataTable' => null,
    'title' => null,
    'content' => false,
    'fixed' => false,
    'card' => true,
    'seamless' => false,
])
@php
    // Support both 'data' and 'dataTable' props for backwards compatibility
    $tableData = $dataTable ?? $data;
    $shouldRenderCard = ($card !== false) && !empty($title) && empty($seamless);
@endphp
@if($tableData)
    @if($shouldRenderCard)
        <div class="card border border-primary shadow-sm mb-3">
            <div class="card-header bg-primary text-white border-bottom-0 py-2">
                <h6 class="mb-0">{{ $title }}</h6>
            </div>
            <div class="card-body">
                {!! $tableData->table(['class' => 'table table-hover datatables no-footer w-100'], true) !!}
            </div>
        </div>
    @else
        <div class="datatable-canvas w-100">
            {!! $tableData->table(['class' => 'table table-hover datatables no-footer w-100'], true) !!}
        </div>
    @endif
@else
<div class="alert alert-warning">
    <i data-lucide="alert-triangle" class="me-2" style="width: 16px; height: 16px;"></i>
    No datatable provided. Please pass the datatable object to this component.
</div>
@endif
@push('scripts')
    <script src="{{ asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/extensions/responsive.min.js') }}"></script>
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
                lengthMenu: [
                    [25, 50, 100, 250, 500],
                    [25, 50, 100, 250, 500]
                ],
                pageLength: 25,
                dom: '<"datatable-header"B><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': document.dir == "rtl" ? '←' : '→',
                        'previous': document.dir == "rtl" ? '→' : '←',
                    }
                },
                buttons: [
                    {
                        extend: 'csv',
                        text: '<i class="fa-regular fa-file-csv me-1"></i> CSV',
                        className: 'btn btn-sm btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-regular fa-file-excel me-1"></i> Excel',
                        className: 'btn btn-sm btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-regular fa-print me-1"></i> Print',
                        className: 'btn btn-sm btn-light',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ],
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1,
                },
                initComplete: function(settings, json) {
                    var api = this.api();
                    $(document).find('.dataTables_paginate .paginate_button a').addClass('rounded-2');
                    setTimeout(function() {
                        api.columns.adjust();
                    }, 50);
                },
                drawCallback: function(settings) {
                    var api = this.api();
                    $(document).find('.dataTables_paginate .paginate_button a').addClass('rounded-2');
                    setTimeout(function() {
                        api.columns.adjust();
                    }, 50);
                },
                ajax: {
                    beforeSend: function(xhr) {
                        let filters = {};
                        $('#filter-container').find('input, select, textarea').each(function() {
                            let $el = $(this);
                            let name = ($el.attr('name') || '').replace('[]', '');
                            let value = $el.val();
                            if ($el.is(':checkbox')) {
                                value = $el.is(':checked') ? 1 : 0;
                            } else if ($el.is(':radio')) {
                                if (!$el.is(':checked')) return;
                            }
                            if (name && value) {
                                filters[name] = value;
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
        $(document).on('shown.bs.dropdown', '.datatables [data-bs-toggle="dropdown"], .datatables .dropdown-toggle', function(e) {
            var $cell = $(this).closest('td');
            $cell.css('z-index', '1060');
            $cell.closest('tr').css('z-index', '1059');
            $(this).parents('.datatables').find('td.dtfc-fixed-right').not($cell).css('z-index', 'auto');
        });
        $(document).on('hidden.bs.dropdown', '.datatables [data-bs-toggle="dropdown"], .datatables .dropdown-toggle', function(e) {
            $(this).closest('td').css('z-index', '2');
            $(this).closest('tr').css('z-index', 'auto');
            $(this).parents('.datatables').find('td.dtfc-fixed-right').css('z-index', '2');
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
    </style>
@endpush
