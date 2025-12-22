<?php

namespace App\DataTables\CheckIns;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\CheckIn;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CheckInDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<CheckIn> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('booking_number', fn($row) => $row->booking_number)
            ->addColumn('guest_name', fn($row) => $row->guest_name)
            ->addColumn('room_number', fn($row) => $row->room->room_number ?? '-')
            ->addColumn('guest_type', fn($row) => badge($row->guest_type === 'national' ? 'National' : 'International'))
            ->addColumn('check_in_date', fn($row) => $row->check_in_date?->format('M d, Y'))
            ->addColumn('check_out_date', fn($row) => $row->check_out_date?->format('M d, Y'))
            ->addColumn('total_amount', fn($row) => '$' . number_format($row->total_amount, 2))
            ->addColumn('status', function($row) {
                $statusColors = [
                    'confirmed' => 'warning',
                    'checked_in' => 'success',
                    'checked_out' => 'info',
                    'cancelled' => 'danger'
                ];
                return badge(ucfirst(str_replace('_', ' ', $row->status)), $statusColors[$row->status] ?? 'secondary');
            })
            ->editColumn('created_at', function (CheckIn $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('check-ins.action', compact('row')))
            ->rawColumns(['action', 'guest_type', 'status']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<CheckIn>
     */
    public function query(CheckIn $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['room.roomType', 'room.status']);

        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (!empty($filters['search'])) {
                $query->where(function($q) use ($filters) {
                    $q->where('booking_number', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('guest_name', 'like', '%' . $filters['search'] . '%')
                      ->orWhereHas('room', function($roomQuery) use ($filters) {
                          $roomQuery->where('room_number', 'like', '%' . $filters['search'] . '%');
                      });
                });
            }

            if (!empty($filters['status'])) {
                if (is_array($filters['status'])) {
                    $query->whereIn('status', $filters['status']);
                } else {
                    $query->where('status', $filters['status']);
                }
            }

            if (!empty($filters['guest_type'])) {
                if (is_array($filters['guest_type'])) {
                    $query->whereIn('guest_type', $filters['guest_type']);
                } else {
                    $query->where('guest_type', $filters['guest_type']);
                }
            }

            if (!empty($filters['check_in_date'])) {
                $query->whereDate('check_in_date', '>=', $filters['check_in_date']);
            }

            if (!empty($filters['check_out_date'])) {
                $query->whereDate('check_out_date', '<=', $filters['check_out_date']);
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
            ->setTableId('check-ins-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('#')
                ->width(30)
                ->addClass('text-center'),
            Column::make('booking_number')->title(__('check-ins.booking_number')),
            Column::make('guest_name')->title(__('check-ins.guest_name')),
            Column::make('room_number')->title(__('check-ins.room_number')),
            Column::make('guest_type')->title(__('check-ins.guest_type')),
            Column::make('check_in_date')->title(__('check-ins.check_in_date')),
            Column::make('check_out_date')->title(__('check-ins.check_out_date')),
            Column::make('total_amount')->title(__('check-ins.total_amount')),
            Column::make('status')->title(__('global.status')),
            Column::make('created_at')->title(__('global.created_at')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->title(__('global.action')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'CheckIns_' . date('YmdHis');
    }
}
