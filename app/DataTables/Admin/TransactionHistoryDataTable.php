<?php

namespace App\DataTables\Admin;

use App\Models\TransactionDetail;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransactionHistoryDataTable extends DataTable
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
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('status', function ($data) {
                return '<span>' . $data->status_label . '</span>';
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', strtolower($keyword));
            })
            ->rawColumns(['status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TransactionDetail $model)
    {
        return $model->where('transaction_id', $this->transaction->id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('transaction-history-table')
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
            Column::computed('DT_RowIndex', '#'),
            Column::make('subscription_reference')->title(__('labels.subscription_reference')),
            Column::make('ipay_transaction_id')->title(__('labels.ipay_transaction_id')),
            Column::make('auth_code')->title(__('labels.auth_code')),
            Column::make('remark')->title(__('labels.remark')),
            Column::make('description')->title(__('labels.description')),
            Column::make('status')->title(__('labels.status')),
            Column::make('created_at')->title(__('labels.created_at')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'TransactionHistory_' . date('YmdHis');
    }
}
