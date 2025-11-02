<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Integration;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IntegrationDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($row) => view('frontend.integrations.action', compact('row')))
            ->setRowId('id')
            ->editColumn('name', function (Integration $model) {
                return $model->getName(app()->getLocale());
            })
            ->editColumn('created_at', function (Integration $model) {
                return $model->created_at?->format('M d, Y');
            });
    }

    public function query(Integration $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('integration-table')
            ->columns($this->getColumns())
            ->minifiedAjax();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
            Column::computed('name')->title(__('root.common.name'))->width(200),
            Column::make('created_at')->title(__('root.common.created_at'))->width(120),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'integrations_' . date('YmdHis');
    }
}
