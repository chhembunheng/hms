<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Client;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ClientDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($a) => view('frontend.clients.action', compact('a')))
            ->setRowId('id')
            ->editColumn('name', function (Client $model) {
                return $model->getName(app()->getLocale());
            })
            ->editColumn('is_active', function (Client $model) {
                return $model->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'is_active'])
            ->editColumn('created_at', function (Client $model) {
                return $model->created_at?->format('M d, Y');
            });
    }

    public function query(Client $model): QueryBuilder
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
            ->setTableId('client-table')
            ->columns($this->getColumns())
            ->minifiedAjax();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
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
        return 'clients_' . date('YmdHis');
    }
}
