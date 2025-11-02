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
            ->addColumn('name', fn($row) => $row->translations->where('locale', $locale)->first()?->name ?? $row->translations->where('locale', 'en')->first()?->name ?? 'N/A')
            ->addColumn('menu', fn($row) => $row->menu?->translations->where('locale', $locale)->first()?->name ?? $row->menu?->translations->where('locale', 'en')->first()?->name ?? 'N/A')
            ->addColumn('action_route', fn($row) => $row->action_route ?? '-')
            ->addColumn('order', fn($row) => $row->order)
            ->addColumn('action', fn($row) => view('settings.permissions.action', compact('row')))
            ->rawColumns(['action']);
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
                    ->minifiedAjax();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('menu'),
            Column::make('action_route'),
            Column::make('order'),
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
