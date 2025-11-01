@props([
    'data' => null,
    'title' => null,
    'content' => false,
    'fixed' => false,
])
<div class="card border border-primary shadow-sm">
    <div class="card-header bg-primary text-white border-bottom-0 py-2">
        <h6 class="mb-0">{{ $title }}</h6>
    </div>
    <div class="card-body">
        {!! $data->table(['class' => 'table table-hover datatables no-footer'], true) !!}
    </div>
</div>
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
                scrollCollapse: true,
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
                            let name = ($(this).attr('name') || '').replace('[]', '');
                            let value = $(this).val();
                            if (name && value) filters[name] = value;
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
