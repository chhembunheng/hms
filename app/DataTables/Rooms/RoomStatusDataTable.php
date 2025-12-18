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
            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('description', fn($row) => $row->description ?? '-')
            ->addColumn('color', fn($row) => '<span style="color: ' . $row->color . ';">' . $row->color . '</span>')
            ->addColumn('is_active', fn($row) => $row->is_active ? 'Active' : 'Inactive')
            ->editColumn('created_at', function (RoomStatus $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('rooms.room-statuses.action', compact('row')))
            ->rawColumns(['action', 'color']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<RoomStatus>
     */
    public function query(RoomStatus $model): QueryBuilder
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

                // Filter by color
                if (!empty($filters['color'])) {
                    $query->where('color', 'like', '%' . $filters['color'] . '%');
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
                    ->setTableId('room-statuses-table')
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
            Column::make('name'),
            Column::make('description'),
            Column::make('color'),
            Column::make('is_active'),
            Column::make('created_at'),
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
