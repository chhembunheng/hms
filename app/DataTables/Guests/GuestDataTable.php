<?php

namespace App\DataTables\Guests;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\Guest;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class GuestDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Guest> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('full_name', fn($row) => $row->full_name)
            ->addColumn('email', fn($row) => $row->email ?: '-')
            ->addColumn('phone', fn($row) => $row->phone ?: '-')
            ->addColumn('guest_type', fn($row) => $row->guest_type ? badge($row->guest_type) : '-')
            ->addColumn('country', fn($row) => $row->country ?: '-')
            ->addColumn('is_blacklisted', fn($row) => $row->is_blacklisted ? badge('Blacklisted', 'danger') : badge('Active', 'success'))
            ->addColumn('total_visits', fn($row) => $row->total_visits ?? 0)
            ->editColumn('created_at', function (Guest $model) {
                return $model->created_at?->format(config('init.datetime.display_format'));
            })
            ->addColumn('action', fn($row) => view('guests.guests.action', compact('row')))
            ->rawColumns(['action', 'guest_type', 'is_blacklisted']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Guest>
     */
    public function query(Guest $model): QueryBuilder
    {
        $query = $model->newQuery();

        $filtersHeader = request()->header('filters');
        if ($filtersHeader) {
            $filters = json_decode(urldecode($filtersHeader), true);

            if (is_array($filters)) {
                if (!empty($filters['search'])) {
                    $query->where(function ($q) use ($filters) {
                        $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('full_name', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('phone', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('national_id', 'like', '%' . $filters['search'] . '%')
                          ->orWhere('passport', 'like', '%' . $filters['search'] . '%');
                    });
                }

                if (!empty($filters['guest_type'])) {
                    $query->where('guest_type', $filters['guest_type']);
                }

                if (!empty($filters['country'])) {
                    $query->where('country', 'like', '%' . $filters['country'] . '%');
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
                    ->setTableId('guests-table')
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
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('full_name')->title(__('guests.full_name')),
            Column::make('email')->title(__('guests.email')),
            Column::make('phone')->title(__('guests.phone')),
            Column::make('guest_type')->title(__('guests.guest_type')),
            Column::make('country')->title(__('guests.country')),
            Column::make('is_blacklisted')->title(__('guests.blacklist_status')),
            Column::make('total_visits')->title(__('guests.total_visits')),
            Column::make('created_at')->title(__('global.created_at')),
            Column::computed('action')
                  ->title(__('global.action'))
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return __('guests.guests') . '_' . date('YmdHis');
    }
}
