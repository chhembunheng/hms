<?php

namespace App\DataTables\Rooms;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\RoomType;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class RoomTypeDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<RoomType> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('description', fn($row) => $row->description ?? '-')
            ->addColumn('is_active', fn($row) => badge($row->is_active ? 'active' : 'inactive'))
            ->editColumn('created_at', function (RoomType $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('rooms.room-types.action', compact('row')))
            ->rawColumns(['action', 'is_active']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<RoomType>
     */
    public function query(RoomType $model): QueryBuilder
    {
        $query = $model->newQuery();

        // Get filters from request headers
        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                // Filter by name
                if (!empty($filters['name'])) {
                    $query->where('name', 'like', '%' . $filters['name'] . '%');
                }

                // Filter by description
                if (!empty($filters['description'])) {
                    $query->where('description', 'like', '%' . $filters['description'] . '%');
                }

                // Filter by is_active
                if (!empty($filters['is_active']) && is_array($filters['is_active'])) {
                    $query->whereIn('is_active', $filters['is_active']);
                }

                // Filter by created_at range
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
                    ->setTableId('room-types-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('name')->title(__('root.common.name')),
            Column::make('description')->title(__('form.description')),
            Column::make('is_active')->title(__('rooms.active_status')),
            Column::make('created_at')->title(__('global.created_at')),
            Column::computed('action')
                  ->title(__('global.action'))
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
        return __('rooms.room_types') . '_' . date('YmdHis');
    }
}
