<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VerificationDataTable extends DataTable
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

                $options = [
                    'no_action' =>  $this->no_action ?? null,
                    'view' => [
                        'permission' => 'merchant.create',
                        'route' => route('verifications.show', ['verification' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'merchant.create',
                        'route' => route('verifications.edit', ['verification' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'merchant.create',
                        'route' => route('verifications.destroy', ['verification' => $data->id])
                    ]
                ];

                if (empty($data->userDetail)) {

                    $options = Arr::only($options, ['delete']);
                }

                return view('components.action', $options)->render();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('phone', function ($data) {
                return $data->formatted_phone_number;
            })
            ->addColumn('profile', function ($data) {
                return $data->userDetail->status_label ?? '<span class="badge badge-info badge-pill px-3">' . __('labels.not_submit') . '</span>';
            })
            ->filterColumn('profile', function ($query, $keyword) {

                $keyword = strtolower($keyword);

                $query->when($keyword == 'not submit', function ($query) {
                    $query->doesntHave('userDetail');
                })->when($keyword != 'not submit', function ($query) use ($keyword) {
                    $query->whereHas('userDetail', function ($query) use ($keyword) {
                        $query->where('status', $keyword);
                    });
                });
            })
            ->rawColumns(['action', 'profile']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        // Get merchants with not submitted user details, pending or rejected details
        return $model->with(['userDetail'])
            ->merchant()
            ->doesntHave('userDetail')
            ->orWhereHas('userDetail', function ($query) {
                $query->pendingDetails()
                    ->orWhere(function ($query) {
                        $query->rejectedDetails();
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
            ->setTableId('verification-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
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
            Column::make('name')->title(__('labels.name'))->width('25%'),
            Column::make('email')->title(__('labels.email'))->width('20%'),
            Column::make('phone')->title(__('labels.contact_no'))->width('15%'),
            Column::make('profile')->title(__('labels.profile'))->width('10%'),
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
        return 'Verfication_' . date('YmdHis');
    }
}
