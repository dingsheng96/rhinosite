<?php

namespace App\DataTables\Admin;

use App\Models\Country;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
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
                        'route' => route('countries.show', ['country' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'country.update',
                        'route' => route('countries.edit', ['country' => $data->id])
                    ],
                    'delete' => [
                        'permission' => 'country.delete',
                        'route' => route('countries.destroy', ['country' => $data->id])
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
            Column::computed('DT_RowIndex', '#')->width('5%'),
            Column::make('name')->title(__('labels.name'))->width('20%'),
            Column::make('dial_code')->title(__('labels.dial_code'))->width('10%'),
            Column::make('currency')->title(__('labels.currency'))->width('20%'),
            Column::make('country_states_count')
                ->searchable(false)
                ->title(trans_choice('labels.country_state', 2))->width('10%'),
            Column::make('cities_count')
                ->searchable(false)
                ->title(trans_choice('labels.city', 2))->width('10%'),
            Column::make('created_at')->title(__('labels.created_at'))->width('15%'),
            Column::computed('action', __('labels.action'))->width('10%')
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
