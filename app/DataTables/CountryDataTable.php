<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\Country;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CountryDataTable extends DataTable
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
                        'route' => route('settings.countries.show', ['country' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'country.update',
                        'route' => route('settings.countries.edit', ['country' => $data->id])
                    ],
                    'delete' => [
                        'permission' => 'country.delete',
                        'route' => route('settings.countries.destroy', ['country' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('currency', function ($data) {
                return $data->currency->name . ' (' . strtoupper($data->currency->code) . ')' ?? '-';
            })
            ->filterColumn('currency', function ($query, $keyword) {
                $query->whereHas('currency', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('code', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Country $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Country $model)
    {
        return $model->withCount(['countryStates', 'cities'])
            ->with(['currency'])
            ->when(!empty($this->currency), function ($query) {
                $query->whereHas('currency', function ($query) {
                    $query->where('id', $this->currency->id);
                });
            })
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('country-table')
            ->addTableClass('table-hover table-bordered table-head-fixed table-striped')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->responsive(true)
            ->autoWidth(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', '#'),
            Column::make('name')->title(__('labels.name')),
            Column::make('dial_code')->title(__('labels.dial_code')),
            Column::make('currency')->title(__('labels.currency')),
            Column::make('country_states_count')
                ->searchable(false)
                ->title(trans_choice('labels.country_state', 2)),
            Column::make('cities_count')
                ->searchable(false)
                ->title(trans_choice('labels.city', 2)),
            Column::make('created_at')->title(__('labels.created_at')),
            Column::computed('action', __('labels.action'))
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
        return 'Country_' . date('YmdHis');
    }
}
