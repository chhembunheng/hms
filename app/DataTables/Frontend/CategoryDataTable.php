<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', fn($row) => view('frontends.categories.action', compact('row')))
            ->setRowId('id')
            ->editColumn('name', function (Category $model) {
                return $model->getName(app()->getLocale());
            })
            ->editColumn('created_at', function (Category $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->rawColumns(['action']);
    }

    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('sort', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('category-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(3);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title(__('root.common.no'))->width(60),
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
        return 'categories_' . date('YmdHis');
    }
}
