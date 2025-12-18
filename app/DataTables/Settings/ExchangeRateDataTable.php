<?php

namespace App\DataTables\Settings;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\ExchangeRate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ExchangeRateDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<ExchangeRate> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('from_currency', fn($row) => $row->from_currency)
            ->addColumn('to_currency', fn($row) => $row->to_currency)
            ->addColumn('rate', fn($row) => number_format($row->rate, 2))
            ->addColumn('effective_date', fn($row) => $row->effective_date->format('Y-m-d'))
            ->addColumn('is_active', fn($row) => $row->is_active ? 'Active' : 'Inactive')
            ->editColumn('created_at', function (ExchangeRate $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('settings.exchange-rates.action', compact('row')))
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ExchangeRate>
     */
    public function query(ExchangeRate $model): QueryBuilder
    {
        $query = $model->newQuery();

        // Get filters from request headers
        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                // Filter by from_currency
                if (!empty($filters['from_currency'])) {
                    $query->where('from_currency', 'like', '%' . $filters['from_currency'] . '%');
                }

                // Filter by to_currency
                if (!empty($filters['to_currency'])) {
                    $query->where('to_currency', 'like', '%' . $filters['to_currency'] . '%');
                }

                // Filter by rate range
                if (!empty($filters['rate_from'])) {
                    $query->where('rate', '>=', $filters['rate_from']);
                }
                if (!empty($filters['rate_to'])) {
                    $query->where('rate', '<=', $filters['rate_to']);
                }

                // Filter by effective_date range
                if (!empty($filters['effective_from'])) {
                    $query->whereDate('effective_date', '>=', $filters['effective_from']);
                }
                if (!empty($filters['effective_to'])) {
                    $query->whereDate('effective_date', '<=', $filters['effective_to']);
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
                    ->setTableId('exchange-rates-table')
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
            Column::make('from_currency'),
            Column::make('to_currency'),
            Column::make('rate'),
            Column::make('effective_date'),
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
        return 'ExchangeRates_' . date('YmdHis');
    }
}
