<?php

namespace App\DataTables\Settings;

use App\Models\Settings\Menu;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MenuDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Menu> $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $locale = app()->getLocale();

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function ($row) use ($locale) {
                return $row->translations
                        ->where('locale', $locale)
                        ->first()?->name
                    ?? $row->translations
                        ->where('locale', 'en')
                        ->first()?->name
                    ?? 'N/A';
            })
            ->addColumn('route', fn ($row) => $row->route ?? '-')
            ->addColumn('sort', fn ($row) => $row->order)
            ->editColumn('created_at', function (Menu $model) {
                return $model->created_at?->format(
                    config('init.datetime.display_format')
                );
            })
            ->addColumn('action', function ($row) {
                return view('settings.menus.action', compact('row'));
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param Menu $model
     * @return QueryBuilder<Menu>
     */
    public function query(Menu $model): QueryBuilder
    {
        return $model->newQuery()->with('translations');
    }

    /**
     * Optional method if you want to use the HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('menu-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5 dataTable no-footer')
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'processing' => true,
                'serverSide' => true,
            ])
            ->orderBy(1);
    }

    /**
     * Get the DataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title(__('root.common.no'))
                ->width(60),

            Column::make('name')
                ->title(__('root.common.name')),

            Column::make('route')
                ->title(__('root.common.route')),

            Column::make('sort')
                ->title(__('root.common.sort')),

            Column::make('created_at')
                ->title(__('root.common.created_at')),

            Column::computed('action')
                ->title(__('root.common.action'))
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Menu_' . date('YmdHis');
    }
}
