<?php

namespace App\DataTables\CheckIns;

use App\Models\CheckIn;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VoidStayDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('check_in_date', function ($row) {
                return $row->check_in_date ? $row->check_in_date->format('d-m-Y') : '';
            })
            ->editColumn('check_out_date', function ($row) {
                return $row->check_out_date ? $row->check_out_date->format('d-m-Y') : '';
            })
            ->editColumn('total_amount', function ($row) {
                return '$' . number_format($row->total_amount, 2);
            })
            ->editColumn('paid_amount', function ($row) {
                return '$' . number_format($row->paid_amount, 2);
            })
            ->editColumn('status', function ($row) {
                $statusColors = [
                    'cancelled' => 'danger',
                    'confirmed' => 'warning',
                    'checked_in' => 'success',
                    'checked_out' => 'info',
                ];

                $color = $statusColors[$row->status] ?? 'secondary';
                return '<span class="badge bg-' . $color . '">' . ucfirst(str_replace('_', ' ', $row->status)) . '</span>';
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at ? $row->updated_at->format('d-m-Y H:i') : '';
            })
            ->rawColumns(['status', 'guest_type', 'action']);
    }

    public function query(CheckIn $model)
    {
        return $model->newQuery()
            ->with(['room.roomType', 'checkInRooms.room'])
            ->where('status', 'cancelled')
            ->orderBy('updated_at', 'desc');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('void-stay-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(
                Button::make('excel'),
                Button::make('csv'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
    }

    protected function getColumns()
    {
        return [
            Column::make('index')->title('#')->searchable(false)->orderable(false)->width(30),
            Column::make('booking_number')->title(__('checkins.booking_number')),
            Column::make('guest_name')->title(__('checkins.guest_name')),
            Column::make('room.room_number')->title(__('checkins.room_number')),
            Column::make('check_in_date')->title(__('checkins.check_in_date')),
            Column::make('check_out_date')->title(__('checkins.check_out_date')),
            Column::make('updated_at')->title(__('checkins.cancelled_date')),
            Column::make('total_amount')->title(__('checkins.total_amount')),
            Column::make('paid_amount')->title(__('checkins.paid_amount')),
            Column::make('guest_type')->title(__('checkins.guest_type')),
            Column::make('status')->title(__('checkins.status'))
        ];
    }

    protected function filename(): string
    {
        return 'VoidStays_' . date('YmdHis');
    }
}
