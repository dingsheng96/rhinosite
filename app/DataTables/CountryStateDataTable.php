<?php

namespace App\DataTables;

use App\Models\CountryState;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CountryStateDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {

                return view('components.action', [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'permission' => 'country.read',
                        'route' => route('settings.countries.country-states.show', ['country' => $this->country_id, 'country_state' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'country.update',
                        'route' => route('settings.countries.country-states.edit', ['country' => $this->country_id, 'country_state' => $data->id])
                    ],
                    'delete' => [
                        'permission' => 'country.delete',
                        'route' => route('settings.countries.country-states.destroy', ['country' => $this->country_id, 'country_state' => $data->id])
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CountryState $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CountryState $model)
    {
        return $model->where('country_id', $this->country_id)
            ->withCount(['cities'])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('country-state-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->responsive(true)
            ->autoWidth(true)
            ->processing(false);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', '#')->width('10%'),
            Column::make('name')->title(__('labels.name'))->width('35%'),
            Column::make('cities_count')->width('15%')
                ->searchable(false)
                ->title(trans_choice('labels.city', 2)),
            Column::make('created_at')->title(__('labels.created_at'))->width('25%'),
            Column::computed('action', __('labels.action'))->width('15%')
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'CountryState_' . date('YmdHis');
    }
}
