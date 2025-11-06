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
            ->addIndexColumn()
            ->addColumn('action', fn($row) => view('frontends.teams.action', compact('row')))
            ->setRowId('id')
            ->addColumn('photo', function (Team $model) {
                if ($model->photo) {
                    return '<a href="' . asset($model->photo) . '" data-bs-popup="lightbox"><img src="' . asset($model->photo) . '" alt="' . $model->getName(app()->getLocale()) . '" style="max-width: 50px; height: auto; border-radius: 4px;"></a>';
                }
                return '<span class="text-muted">No photo</span>';
            })
            ->editColumn('name', function (Team $model) {
                return $model->getName(app()->getLocale());
            })
            ->addColumn('phone', function (Team $model) {
                if ($model->email) {
                    return '<a href="mailto:' . $model->email . '">' . $model->email . '</a>';
                }
                return '<span class="text-muted">Not phone</span>';
            })
            ->editColumn('created_at', function (Team $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->rawColumns(['action', 'photo', 'phone']);
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
            ->minifiedAjax()
            ->orderBy(2);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title(__('root.common.no'))->width(60),
            Column::computed('photo')->title(__('root.common.photo'))->width(80)->addClass('text-center'),
            Column::computed('name')->title(__('root.common.name'))->width(200),
            Column::computed('phone')->title('Phone/Email')->width(150),
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
