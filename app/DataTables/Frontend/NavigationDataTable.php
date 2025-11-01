<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Navigation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NavigationDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($a) => view('frontend.navigations.action', compact('a')))
            ->setRowId('id')
            ->editColumn('name', function (Navigation $model) {
                return $model->getName(app()->getLocale());
            })
            ->editColumn('label', function (Navigation $model) {
                return $model->getLabel(app()->getLocale());
            })
            ->editColumn('created_at', function (Navigation $model) {
                return $model->created_at?->format('M d, Y');
            });
    }

    public function query(Navigation $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('sort', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('navigation-table')
            ->columns($this->getColumns())
            ->minifiedAjax();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
            Column::computed('name')->title(__('root.common.name'))->width(200),
            Column::computed('label')->title(__('root.common.label'))->width(150),
            Column::make('url')->title(__('root.common.url'))->width(200),
            Column::make('icon')->title(__('root.common.icon'))->width(100),
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
        return 'navigations_' . date('YmdHis');
    }
}
