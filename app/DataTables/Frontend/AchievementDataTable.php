<?php

namespace App\DataTables\Frontend;

use App\Models\Frontend\Achievement;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AchievementDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', fn($row) => view('frontend.achievements.action', compact('row')))
            ->setRowId('id')
            ->editColumn('title', function (Achievement $model) {
                return $model->getTitle(app()->getLocale());
            })
            ->editColumn('created_at', function (Achievement $model) {
                return $model->created_at?->format('M d, Y');
            });
    }

    public function query(Achievement $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('translations')
            ->whereNull('deleted_at')
            ->orderBy('sort', 'asc');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('achievement-table')
            ->columns($this->getColumns())
            ->minifiedAjax();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(60),
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
        return 'achievements_' . date('YmdHis');
    }
}
