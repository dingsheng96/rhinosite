<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Support\Facades\PriceFacade;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
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
                        'permission' => 'order.read',
                        'route' => route('orders.show', ['order' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'order.update',
                        'route' => route('orders.edit', ['order' => $data->id]),
                    ]
                ])->render();
            })
            ->addColumn('user', function ($data) {
                return $data->user->name;
            })
            ->editColumn('status', function ($data) {
                return '<span>' . $data->status_label . '</span>';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('grand_total', function ($data) {
                return $data->grand_total_with_currency;
            })
            ->filterColumn('grand_total', function ($query, $keyword) {
                return $query->where('grand_total', PriceFacade::convertFloatToInt($keyword));
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        return $model->when(Auth::user()->is_merchant, function ($query) {
            $query->where('user_id', Auth::id());
        })->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('order-table')
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
        $columns =  [
            Column::computed('DT_RowIndex', '#')->width('5%'),
            Column::make('order_no')->title(__('labels.order_no'))->width('15%'),
            Column::make('user')->title(__('labels.user'))->width('20%'),
            Column::make('grand_total')->title(__('labels.grand_total'))->width('15%'),
            Column::make('total_items')->title(trans_choice('labels.item', 2))->width('10%'),
            Column::make('status')->title(__('labels.status'))->width('10%'),
            Column::make('created_at')->title(__('labels.created_at'))->width('15%'),
            Column::computed('action', __('labels.action'))->width('10%')
                ->exportable(false)
                ->printable(false),
        ];

        if (Auth::user()->is_merchant) {
            $columns = Arr::except($columns, 2);
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Order_' . date('YmdHis');
    }
}
