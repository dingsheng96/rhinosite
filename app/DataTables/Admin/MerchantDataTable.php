<?php

namespace App\DataTables\Admin;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MerchantDataTable extends DataTable
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
                return view('admin.components.btn_action', [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'permission' => 'merchant.read',
                        'route' => route('admin.merchants.show', ['merchant' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'merchant.update',
                        'route' => route('admin.merchants.edit', ['merchant' => $data->id])
                    ],
                    'delete' => [
                        'permission' => 'merchant.delete',
                        'route' => route('admin.merchants.destroy', ['merchant' => $data->id])
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('status', function ($data) {
                return $data->status_label . '<br>' . $data->profile_status_label;
            })
            ->editColumn('phone', function ($data) {
                return $data->formatted_phone_number;
            })
            ->filterColumn('status', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                $query->where('status', $keyword)
                    ->orWhere(function ($query) use ($keyword) {
                        $query->when($keyword == 'verified', function ($query) {
                            $query->whereHas('userDetail', function ($query) {
                                $query->approvedDetails();
                            });
                        })->when($keyword == 'unverified', function ($query) {
                            $query->doesntHave('userDetail')
                                ->orWhereHas('userDetail', function ($query) {
                                    $query->where(function ($query) {
                                        $query->pendingDetails();
                                    })->orWhere(function ($query) {
                                        $query->rejectedDetails();
                                    });
                                });
                        });
                    });
            })
            ->rawColumns(['action', 'status', 'profile']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        // Get merchant with approved completed user details

        return $model->with(['userDetail'])
            ->merchant()->whereHas('userDetail', function ($query) {
                $query->approvedDetails();
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
            ->setTableId('merchant-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5, 'desc')
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
            Column::make('name')->title(__('labels.name')),
            Column::make('email')->title(__('labels.email')),
            Column::make('phone')->title(__('labels.contact_no')),
            Column::make('status')->title(__('labels.status')),
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
        return 'Merchant_' . date('YmdHis');
    }
}
