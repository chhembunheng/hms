<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Team;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TeamDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($row) => view('frontend.teams.action', compact('row')))
            ->setRowId('id')
            ->editColumn('name', function (Team $model) {
                return $model->getName(app()->getLocale());
            })
            ->editColumn('created_at', function (Team $model) {
                return $model->created_at?->format('M d, Y');
            });
    }

    public function query(Team $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('team-table')
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
        return 'teams_' . date('YmdHis');
    }
}
