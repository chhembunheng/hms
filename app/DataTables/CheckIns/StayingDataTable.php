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

class StayingDataTable extends DataTable
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
                return badge(ucfirst(str_replace('_', ' ', $row->status)));
            })
            ->editColumn('created_at', function (CheckIn $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('check-ins.staying.action', compact('row')))
            ->rawColumns(['guest_type', 'status', 'action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param CheckIn $model
     * @return QueryBuilder
     */
    public function query(CheckIn $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['room.roomType', 'room.status'])
            ->where('status', 'checked_in');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('staying-table')
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
            Column::computed('DT_RowIndex')
                  ->title('#')
                  ->width(30)
                  ->addClass('text-center'),
            Column::make('booking_number')->title(__('rooms.booking_number')),
            Column::make('guest_name')->title(__('rooms.guest_name')),
            Column::make('room_number')->title(__('rooms.room_number')),
            Column::make('guest_type')->title(__('rooms.guest_type')),
            Column::make('check_in_date')->title(__('rooms.check_in_date')),
            Column::make('check_out_date')->title(__('rooms.check_out_date')),
            Column::make('total_amount')->title(__('rooms.total_amount')),
            Column::make('status')->title(__('form.status')),
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
        return 'StayingGuests_' . date('YmdHis');
    }
}
