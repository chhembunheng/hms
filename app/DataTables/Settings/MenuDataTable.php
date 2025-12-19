<?php

namespace App\DataTables\Settings;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\Settings\Menu;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class MenuDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Menu> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $locale = app()->getLocale();
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', fn($row) => $row->translations->where('locale', $locale)->first()?->name ?? $row->translations->where('locale', 'en')->first()?->name ?? 'N/A')
            ->addColumn('route', fn($row) => $row->route ?? '-')
            ->addColumn('sort', fn($row) => $row->order)
            ->editColumn('created_at', function (Menu $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('settings.menus.action', compact('row')))
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Menu>
     */
    public function query(Menu $model): QueryBuilder
    {
        $query = $model->newQuery()->with('translations');

        // Get filters from request headers
        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                // Filter by name (searches in translations)
                if (!empty($filters['name'])) {
                    $query->whereHas('translations', function ($q) use ($filters) {
                        $q->where('name', 'like', '%' . $filters['name'] . '%');
                    });
                }

                // Filter by route
                if (!empty($filters['route'])) {
                    $query->where('route', 'like', '%' . $filters['route'] . '%');
                }

                // Filter by parent menu
                if (!empty($filters['parent_id'])) {
                    if ($filters['parent_id'] === 'null') {
                        $query->whereNull('parent_id');
                    } else {
                        $query->where('parent_id', $filters['parent_id']);
                    }
                }

                // Filter by multiple menu IDs (multiselect)
                if (!empty($filters['menu_ids']) && is_array($filters['menu_ids'])) {
                    $query->whereIn('id', $filters['menu_ids']);
                }

                // Filter by sort/order range
                if (!empty($filters['sort_from'])) {
                    $query->where('sort', '>=', $filters['sort_from']);
                }
                if (!empty($filters['sort_to'])) {
                    $query->where('sort', '<=', $filters['sort_to']);
                }

                // Filter by date range
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
                    ->setTableId('menu-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
            //         ->parameters([
            //     'processing' => true,
            //     'serverSide' => true,
            //     'searchDelay' => 500,
            //     'deferLoading' => 0,
            // ]);

    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('#')->width(60),
            Column::make('name')->title(__('root.common.name')),
            Column::make('route')->title(__('form.route')),
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
        return __('global.menus') . '_' . date('YmdHis');
    }
}
