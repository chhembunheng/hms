<?php

namespace App\DataTables\Settings;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\Settings\Permission;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PermissionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Permission> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $locale = app()->getLocale();
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function($row) use ($locale) {
                $name = $row->translations->where('locale', $locale)->first()?->name ?? $row->translations->where('locale', 'en')->first()?->name ?? 'N/A';

                // Add badge based on permission action
                $badgeClass = 'bg-secondary';
                $icon = 'fa-shield-halved';

                if (str_contains(strtolower($row->action ?? ''), 'add')) {
                    $badgeClass = 'bg-success';
                    $icon = 'fa-circle-plus';
                } elseif (str_contains(strtolower($row->action ?? ''), 'edit')) {
                    $badgeClass = 'bg-info';
                    $icon = 'fa-pen-to-square';
                } elseif (str_contains(strtolower($row->action ?? ''), 'delete')) {
                    $badgeClass = 'bg-danger';
                    $icon = 'fa-trash';
                } elseif (str_contains(strtolower($row->action ?? ''), 'view')) {
                    $badgeClass = 'bg-primary';
                    $icon = 'fa-eye';
                }

                return '<i class="fa-solid ' . $icon . ' me-2 text-muted"></i>' . $name;
            })
            ->addColumn('menu', fn($row) => $row->menu?->translations->where('locale', $locale)->first()?->name ?? $row->menu?->translations->where('locale', 'en')->first()?->name ?? 'N/A')
            ->addColumn('action_route', fn($row) => $row->action_route ?? '-')
            ->addColumn('sort', fn($row) => '<span class="badge bg-secondary bg-opacity-20 text-secondary">' . ($row->sort ?? 0) . '</span>')
            ->editColumn('created_at', function (Permission $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('settings.permissions.action', compact('row')))
            ->rawColumns(['name', 'sort', 'action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Permission>
     */
    public function query(Permission $model): QueryBuilder
    {
        return $model->newQuery()->with(['menu', 'translations']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('permission-table')
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
            Column::computed('DT_RowIndex')->title(__('root.common.no'))->width(60),
            Column::make('name'),
            Column::make('menu'),
            Column::make('action_route'),
            Column::make('sort'),
            Column::make('created_at'),
            Column::computed('action')
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
        return 'Permission_' . date('YmdHis');
    }
}
