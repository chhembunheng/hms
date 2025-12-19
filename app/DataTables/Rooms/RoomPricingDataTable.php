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
            ->addColumn('room_type', fn($row) => $row->roomType->name ?? '-')
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

        // Get filters from request headers
        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                // Filter by room_type_id
                if (!empty($filters['room_type_id'])) {
                    $query->where('room_type_id', $filters['room_type_id']);
                }

                // Filter by price range
                if (!empty($filters['price_min'])) {
                    $query->where('price', '>=', $filters['price_min']);
                }
                if (!empty($filters['price_max'])) {
                    $query->where('price', '<=', $filters['price_max']);
                }

                // Filter by currency
                if (!empty($filters['currency'])) {
                    $query->where('currency', $filters['currency']);
                }

                // Filter by pricing_type
                if (!empty($filters['pricing_type'])) {
                    $query->where('pricing_type', $filters['pricing_type']);
                }

                // Filter by effective_from range
                if (!empty($filters['effective_from'])) {
                    $query->whereDate('effective_from', '>=', $filters['effective_from']);
                }
                if (!empty($filters['effective_to'])) {
                    $query->whereDate('effective_to', '<=', $filters['effective_to']);
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
