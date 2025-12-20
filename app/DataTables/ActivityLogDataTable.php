<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\ActivityLog;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ActivityLogDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<ActivityLog> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('user_name', fn($row) => $row->user ? $row->user->name : 'System')
            ->addColumn('action_type', fn($row) => ucfirst($row->action))
            ->addColumn('model', fn($row) => class_basename($row->model_type) . ' #' . $row->model_id)
            ->editColumn('created_at', function (ActivityLog $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('activity-logs.action', compact('row')))
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ActivityLog>
     */
    public function query(ActivityLog $model): QueryBuilder
    {
        $query = $model->newQuery()->with('user');

        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                if (!empty($filters['search'])) {
                    $query->where(function ($q) use ($filters) {
                        $q->where('action', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('model_type', 'like', '%' . $filters['search'] . '%')
                          ->orWhereHas('user', function ($subQuery) use ($filters) {
                              $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
                          });
                    });
                }

                if (!empty($filters['user_id'])) {
                    $query->whereIn('user_id', (array) $filters['user_id']);
                }

                if (!empty($filters['action'])) {
                    $query->whereIn('action', (array) $filters['action']);
                }

                if (!empty($filters['model_type'])) {
                    $query->whereIn('model_type', (array) $filters['model_type']);
                }

                if (!empty($filters['created_from'])) {
                    $query->whereDate('created_at', '>=', $filters['created_from']);
                }

                if (!empty($filters['created_to'])) {
                    $query->whereDate('created_at', '<=', $filters['created_to']);
                }
            }
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('activity-log-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->parameters([
                        'responsive' => true,
                        'autoWidth' => false,
                        'scrollX' => true,
                        'fixedColumns' => [
                            'leftColumns' => 1,
                            'rightColumns' => 1,
                        ],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('#')->width(60),
            Column::make('user_name')->title(__('global.user')),
            Column::make('action_type')->title(__('global.action')),
            Column::make('model')->title(__('global.model')),
            Column::make('ip_address')->title(__('global.ip_address')),
            Column::make('created_at')->title(__('global.created_at')),
            Column::computed('action')
                  ->title(__('global.action'))
                  ->exportable(false)
                  ->printable(false)
                  ->width(100)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return __('global.activity_logs') . '_' . date('YmdHis');
    }
}
