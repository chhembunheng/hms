<?php

namespace App\DataTables\Rooms;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\RoomPricing;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class RoomPricingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<RoomPricing> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('room_type', fn($row) => $row->roomType->localized_name ?? '-')
            ->addColumn('price', fn($row) => number_format($row->price, 2))
            ->addColumn('pricing_type', fn($row) => RoomPricing::getPricingTypes()[$row->pricing_type] ?? $row->pricing_type)
            ->addColumn('currency', fn($row) => $row->currency)
            ->addColumn('effective_from', fn($row) => $row->effective_from?->format(config('init.datetime.display_format')))
            ->addColumn('effective_to', fn($row) => $row->effective_to?->format(config('init.datetime.display_format')) ?? '-')
            ->addColumn('is_active', fn($row) => badge($row->is_active ? 'active' : 'inactive'))
            ->editColumn('created_at', function (RoomPricing $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('rooms.room-pricing.action', compact('row')))
            ->rawColumns(['action', 'is_active']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<RoomPricing>
     */
    public function query(RoomPricing $model): QueryBuilder
    {
        $query = $model->newQuery()->with('roomType');

        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                if (!empty($filters['search'])) {
                    $query->where(function ($q) use ($filters) {
                        $q->where('currency', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('pricing_type', 'like', '%' . $filters['search'] . '%')
                          ->orWhereHas('roomType', function ($subQuery) use ($filters) {
                              $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
                          });
                    });
                }

                if (!empty($filters['room_type_id'])) {
                    if (is_array($filters['room_type_id'])) {
                        $query->whereIn('room_type_id', $filters['room_type_id']);
                    } else {
                        $query->where('room_type_id', $filters['room_type_id']);
                    }
                }

                if (!empty($filters['pricing_type'])) {
                    if (is_array($filters['pricing_type'])) {
                        $query->whereIn('pricing_type', $filters['pricing_type']);
                    } else {
                        $query->where('pricing_type', $filters['pricing_type']);
                    }
                }

                if (!empty($filters['currency'])) {
                    if (is_array($filters['currency'])) {
                        $query->whereIn('currency', $filters['currency']);
                    } else {
                        $query->where('currency', $filters['currency']);
                    }
                }

                if (!empty($filters['is_active']) && is_array($filters['is_active'])) {
                    $query->whereIn('is_active', $filters['is_active']);
                }

                if (!empty($filters['effective_from'])) {
                    $query->whereDate('effective_from', '>=', $filters['effective_from']);
                }

                if (!empty($filters['effective_to'])) {
                    $query->whereDate('effective_to', '<=', $filters['effective_to']);
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
                    ->setTableId('room-pricing-table')
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
            Column::make('room_type')->title(__('rooms.room_type')),
            Column::make('price')->title(__('rooms.price')),
            Column::make('pricing_type')->title(__('rooms.pricing_type')),
            Column::make('currency')->title(__('rooms.currency')),
            Column::make('effective_from')->title(__('rooms.effective_from')),
            Column::make('effective_to')->title(__('rooms.effective_to')),
            Column::make('is_active')->title(__('rooms.is_active')),
            Column::make('created_at')->title(__('Created At')),
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
        return 'RoomPricing_' . date('YmdHis');
    }
}
