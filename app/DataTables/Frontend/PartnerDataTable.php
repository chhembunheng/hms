<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Partner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PartnerDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($row) => view('frontend.partners.action', compact('row')))
            ->setRowId('id')
            ->addColumn('logo', function (Partner $model) {
                if ($model->logo) {
                    return '<a href="' . asset($model->logo) . '" data-bs-popup="lightbox"><img src="' . asset($model->logo) . '" alt="' . $model->getName(app()->getLocale()) . '" style="max-width: 50px; height: auto; border-radius: 4px;"></a>';
                }
                return '<span class="text-muted">No logo</span>';
            })
            ->editColumn('name', function (Partner $model) {
                return $model->getName(app()->getLocale());
            })
            ->editColumn('is_active', function (Partner $model) {
                return badge($model->is_active ? 'active' : 'inactive');
            })
            ->rawColumns(['action', 'is_active', 'logo'])
            ->editColumn('created_at', function (Partner $model) {
                return $model->created_at?->format('M d, Y');
            });
    }

    public function query(Partner $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('partner-table')
            ->columns($this->getColumns())
            ->minifiedAjax();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
            Column::computed('logo')->title(__('root.common.logo'))->width(80)->addClass('text-center'),
            Column::computed('name')->title(__('root.common.name'))->width(200),
            Column::make('sort')->title(__('root.common.sort'))->width(80),
            Column::computed('is_active')->title(__('root.common.status'))->width(100),
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
        return 'partners_' . date('YmdHis');
    }
}
