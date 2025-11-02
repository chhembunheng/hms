<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Service;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ServiceDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($row) => view('frontend.services.action', compact('row')))
            ->setRowId('id')
            ->editColumn('name', function (Service $model) {
                return $model->getName(app()->getLocale());
            })
            ->editColumn('slug', function (Service $model) {
                return $model->slug;
            })
            ->editColumn('created_at', function (Service $model) {
                return $model->created_at?->format('M d, Y');
            });
    }

    public function query(Service $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('sort', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('service-table')
            ->columns($this->getColumns())
            ->minifiedAjax();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
            Column::make('slug')->title(__('root.common.slug'))->width(180),
            Column::computed('name')->title(__('root.common.name'))->width(200),
            Column::make('sort')->title(__('root.common.sort'))->width(100),
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
        return 'services_' . date('YmdHis');
    }
}
