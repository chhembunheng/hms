<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\CheckIn;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CheckoutDataTable extends DataTable
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
            ->addColumn('room_number', fn($row) => $row->rooms->pluck('room_number')->join(', '))
            ->addColumn('total_amount', fn($row) => number_format($row->total_amount, 2))
            ->addColumn('paid_amount', fn($row) => number_format($row->paid_amount, 2))
            ->addColumn('remaining_amount', fn($row) => number_format($row->remaining_amount, 2))
            ->editColumn('actual_check_out_at', function (CheckIn $model) {
                return $model->actual_check_out_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('checkouts.action', compact('row')))
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<CheckIn>
     */
    public function query(CheckIn $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['rooms', 'guest'])->where('status', 'checked_out');

        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                if (!empty($filters['search'])) {
                    $query->where(function ($q) use ($filters) {
                        $q->where('booking_number', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('guest_name', 'like', '%' . $filters['search'] . '%')
                          ->orWhereHas('rooms', function ($subQuery) use ($filters) {
                              $subQuery->where('room_number', 'like', '%' . $filters['search'] . '%');
                          });
                    });
                }

                if (!empty($filters['check_out_from'])) {
                    $query->whereDate('actual_check_out_at', '>=', $filters['check_out_from']);
                }

                if (!empty($filters['check_out_to'])) {
                    $query->whereDate('actual_check_out_at', '<=', $filters['check_out_to']);
                }

                if (!empty($filters['paid_status'])) {
                    if ($filters['paid_status'] === 'paid') {
                        $query->whereRaw('paid_amount >= total_amount');
                    } elseif ($filters['paid_status'] === 'partial') {
                        $query->whereRaw('paid_amount > 0 AND paid_amount < total_amount');
                    } elseif ($filters['paid_status'] === 'unpaid') {
                        $query->where('paid_amount', 0);
                    }
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
                    ->setTableId('checkouts-table')
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
            Column::make('booking_number')->title(__('checkins.booking_number')),
            Column::make('guest_name')->title(__('checkins.guest_name')),
            Column::make('room_number')->title(__('checkins.room_number')),
            Column::make('total_amount')->title(__('checkins.total_amount')),
            Column::make('paid_amount')->title(__('checkins.paid_amount')),
            Column::make('remaining_amount')->title(__('checkins.remaining_amount')),
            Column::make('actual_check_out_at')->title(__('checkins.check_out_date')),
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
        return __('checkins.checkouts') . '_' . date('YmdHis');
    }
}
