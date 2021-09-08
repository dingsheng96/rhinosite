<?php

namespace App\DataTables\Admin;

use App\Models\Order;
use App\Models\UserDetail;
use App\Models\Transaction;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransactionDataTable extends DataTable
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
            // ->addColumn('action', function ($data) {
            //     return view('components.action', [
            //         'no_action' => $this->no_action ?: null,
            //         'view' => [
            //             'permission' => 'transaction.create',
            //             'route' => route('transactions.show', ['transaction' => $data->id])
            //         ],
            //     ])->render();
            // })
            ->addColumn('order_no', function ($data) {
                return optional($data->sourceable)->order_no ?? '-';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('status', function ($data) {
                return '<span>' . $data->status_label . '</span>';
            })
            ->editColumn('amount', function ($data) {
                return $data->amount_with_currency_code;
            })
            ->editColumn('payment_method', function ($data) {
                return $data->paymentMethod->name;
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', strtolower($keyword));
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model)
    {
        return $model->with(['sourceable', 'paymentMethod'])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('transaction-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(6, 'desc')
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
            Column::make('transaction_no')->title(__('labels.transaction_no'))->width('15%'),
            Column::make('order_no')->title(__('labels.order_no'))->width('15%'),
            Column::make('amount')->title(__('labels.amount'))->width('15%'),
            Column::make('payment_method')->title(__('labels.payment_method'))->width('15%'),
            Column::make('status')->title(__('labels.status'))->width('10%'),
            Column::make('created_at')->title(__('labels.created_at'))->width('15%'),
            // Column::computed('action', __('labels.action'))->width('10%')
            //     ->exportable(false)
            //     ->printable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Transaction_' . date('YmdHis');
    }
}
