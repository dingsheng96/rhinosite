<?php

namespace App\DataTables\Admin;

use App\Models\Currency;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CurrencyDataTable extends DataTable
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
                        'permission' => 'currency.read',
                        'route' => route('currencies.show', ['currency' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'currency.update',
                        'route' => route('currencies.edit', ['currency' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'currency.delete',
                        'route' => route('currencies.destroy', ['currency' => $data->id])
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
     * @param \App\Models\Currency $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Currency $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('currency-table')
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
            Column::make('name')->title(__('labels.name'))->width('50%'),
            Column::make('code')->title(__('labels.code'))->width('20%'),
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
