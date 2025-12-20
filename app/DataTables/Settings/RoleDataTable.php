<?php

namespace App\DataTables\Settings;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\Settings\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class RoleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Role> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $locale = app()->getLocale();
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', fn($row) => $row->translations->where('locale', $locale)->first()?->name ?? $row->translations->where('locale', 'en')->first()?->name ?? 'N/A')
            ->editColumn('created_at', function (Role $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->editColumn('administrator', fn($row) => $row->administrator ? badge(__('global.yes')) : badge(__('global.no')))
            ->addColumn('total_users', fn($row) => $row->users()->count())
            ->addColumn('action', fn($row) => view('settings.roles.action', compact('row')))
            ->rawColumns(['action', 'administrator']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Role>
     */
    public function query(Role $model): QueryBuilder
    {
        $query = $model->newQuery()->with('translations');

        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                if (!empty($filters['search'])) {
                    $query->whereHas('translations', function ($subQuery) use ($filters) {
                        $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
                    });
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
                    ->setTableId('role-table')
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
            Column::make('name')->title(__('root.common.name')),
            Column::make('administrator')->title(__('form.administrator')),
            Column::make('total_users')->title(__('form.total_users')),
            Column::make('sort')->title(__('form.sort')),
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
        return __('global.roles') . '_' . date('YmdHis');
    }
}
