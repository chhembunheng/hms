<?php

namespace App\DataTables\Rooms;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\RoomStatus;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class RoomStatusDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<RoomStatus> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', fn($row) => $row->localized_name)
            ->addColumn('name_en', fn($row) => $row->name_en ?? '-')
            ->addColumn('name_kh', fn($row) => $row->name_kh ?? '-')
            ->addColumn('description', fn($row) => $row->description ?? '-')
            ->addColumn('color', fn($row) => '<span style="color: ' . $row->color . ';">' . $row->color . '</span>')
            ->addColumn('is_active', fn($row) => badge($row->is_active ? 'active' : 'inactive'))
            ->editColumn('created_at', function (RoomStatus $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('rooms.room-statuses.action', compact('row')))
            ->rawColumns(['action', 'color', 'is_active']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<RoomStatus>
     */
    public function query(RoomStatus $model): QueryBuilder
    {
        $query = $model->newQuery();

        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                if (!empty($filters['search'])) {
                    $query->where('name_en', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('name_kh', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('color', 'like', '%' . $filters['search'] . '%');
                }

                if (!empty($filters['is_active']) && is_array($filters['is_active'])) {
                    $query->whereIn('is_active', $filters['is_active']);
                }

                if (!empty($filters['created_from'])) {
                    $query->whereDate('created_at', '>=', $filters['created_from']);
                }

                if (!empty($filters['created_to'])) {
                    $query->whereDate('created_at', '<=', $filters['created_to']);
                }
            }
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('room-statuses-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false,
                        'scrollX' => true,
                        'fixedColumns' => [
                            'leftColumns' => 1,
                            'rightColumns' => 1,
                        ],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('name_en')->title(__('rooms.name_en')),
            Column::make('name_kh')->title(__('rooms.name_kh')),
            Column::make('description')->title(__('form.description')),
            Column::make('color')->title(__('rooms.color')),
            Column::make('is_active')->title(__('rooms.active_status')),
            Column::make('created_at')->title(__('global.created_at')),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'RoomStatuses_' . date('YmdHis');
    }
}
