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
            ->addColumn('floor', fn($row) => $row->floor?->localized_name ?? '-')
            ->addColumn('room_type', fn($row) => $row->roomType?->localized_name ?? '-')
            ->addColumn('status', fn($row) => roomStatusBadgeWithColor($row->status))
            ->addColumn('is_active', fn($row) => badge($row->is_active ? 'active' : 'inactive'))
            ->editColumn('created_at', function (Room $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('rooms.rooms.action', compact('row')))
            ->rawColumns(['action', 'is_active', 'status']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Room>
     */
    public function query(Room $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['roomType', 'status', 'floor']);

        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                if (!empty($filters['search'])) {
                    $query->where(function ($q) use ($filters) {
                        $q->where('room_number', 'like', '%' . $filters['search'] . '%')
                          ->orWhereHas('floor', function ($subQuery) use ($filters) {
                              $subQuery->where('name_en', 'like', '%' . $filters['search'] . '%')
                                       ->orWhere('name_kh', 'like', '%' . $filters['search'] . '%');
                          })
                          ->orWhereHas('roomType', function ($subQuery) use ($filters) {
                              $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
                          })
                          ->orWhereHas('status', function ($subQuery) use ($filters) {
                              $subQuery->where('name_en', 'like', '%' . $filters['search'] . '%')
                                       ->orWhere('name_kh', 'like', '%' . $filters['search'] . '%');
                          });
                    });
                }

                if (!empty($filters['floor'])) {
                    $query->where('floor', $filters['floor']);
                }

                if (!empty($filters['room_type_id'])) {
                    if (is_array($filters['room_type_id'])) {
                        $query->whereIn('room_type_id', $filters['room_type_id']);
                    } else {
                        $query->where('room_type_id', $filters['room_type_id']);
                    }
                }

                if (!empty($filters['floor_id'])) {
                    if (is_array($filters['floor_id'])) {
                        $query->whereIn('floor_id', $filters['floor_id']);
                    } else {
                        $query->where('floor_id', $filters['floor_id']);
                    }
                }

                if (!empty($filters['status_id'])) {
                    if (is_array($filters['status_id'])) {
                        $query->whereIn('status_id', $filters['status_id']);
                    } else {
                        $query->where('status_id', $filters['status_id']);
                    }
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
                    ->setTableId('rooms-table')
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
