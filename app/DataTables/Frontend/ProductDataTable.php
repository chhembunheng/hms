<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', fn($row) => view('frontend.products.action', compact('row')))
            ->setRowId('id')
            ->addColumn('image', function (Product $model) {
                if ($model->image) {
                    return '<a href="' . asset($model->image) . '" data-bs-popup="lightbox"><img src="' . asset($model->image) . '" alt="' . $model->getName(app()->getLocale()) . '" style="max-width: 50px; height: auto; border-radius: 4px;"></a>';
                }
                return '<span class="text-muted">No image</span>';
            })
            ->editColumn('name', function (Product $model) {
                return $model->getName(app()->getLocale());
            })
            ->editColumn('created_at', function (Product $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('sort', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(3);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title(__('root.common.no'))->width(60),
            Column::make('sku')->title('SKU')->width(120),
            Column::computed('image')->title(__('root.common.image'))->width(80)->addClass('text-center'),
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
        return 'products_' . date('YmdHis');
    }
}
