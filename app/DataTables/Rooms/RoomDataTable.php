<?php

namespace App\DataTables\Rooms;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\Room;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class RoomDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Room> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('room_number', fn($row) => $row->room_number)
            ->addColumn('floor', fn($row) => $row->floor ?? '-')
            ->addColumn('room_type', fn($row) => $row->roomType?->name ?? '-')
            ->addColumn('status', fn($row) => $row->status?->name ?? '-')
            ->addColumn('is_active', fn($row) => badge($row->is_active ? 'active' : 'inactive'))
            ->editColumn('created_at', function (Room $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('rooms.rooms.action', compact('row')))
            ->rawColumns(['action', 'is_active']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Room>
     */
    public function query(Room $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['roomType', 'status']);

        // Get filters from request headers
        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                // Filter by room_number
                if (!empty($filters['room_number'])) {
                    $query->where('room_number', 'like', '%' . $filters['room_number'] . '%');
                }

                // Filter by floor
                if (!empty($filters['floor'])) {
                    $query->where('floor', $filters['floor']);
                }

                // Filter by room_type_id
                if (!empty($filters['room_type_id'])) {
                    $query->where('room_type_id', $filters['room_type_id']);
                }

                // Filter by status_id
                if (!empty($filters['status_id'])) {
                    $query->where('status_id', $filters['status_id']);
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
                    ->setTableId('rooms-table')
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
            Column::make('room_number')->title(__('rooms.room_number')),
            Column::make('floor')->title(__('rooms.floor')),
            Column::make('room_type')->title(__('rooms.room_type')),
            Column::make('status')->title(__('form.status')),
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
        return __('rooms.rooms') . '_' . date('YmdHis');
    }
}
