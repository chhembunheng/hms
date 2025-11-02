<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Choosing;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ChoosingDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($row) => view('frontend.choosings.action', compact('row')))
            ->setRowId('id')
            ->addColumn('image', function (Choosing $model) {
                if ($model->image) {
                    return '<a href="' . asset($model->image) . '" data-bs-popup="lightbox"><img src="' . asset($model->image) . '" alt="' . $model->getTitle(app()->getLocale()) . '" style="max-width: 50px; height: auto; border-radius: 4px;"></a>';
                }
                return '<span class="text-muted">No image</span>';
            })
            ->editColumn('title', function (Choosing $model) {
                return $model->getTitle(app()->getLocale());
            })
            ->editColumn('created_at', function (Choosing $model) {
                return $model->created_at?->format('M d, Y');
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(Choosing $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('sort', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('choosing-table')
            ->columns($this->getColumns())
            ->minifiedAjax();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
            Column::computed('image')->title(__('root.common.image'))->width(80)->addClass('text-center'),
            Column::computed('title')->title(__('root.common.title'))->width(200),
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
        return 'choosings_' . date('YmdHis');
    }
}
