<?php

namespace App\DataTables;

use App\Models\Registration;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RegistrationDataTable extends DataTable
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

                $actions = [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'permission' => 'merchant.create',
                        'route' => route('users.registrations.show', ['registration' => $data->id])
                    ]
                ];

                if ($data->status == Registration::STATUS_PENDING) {
                    $update = [
                        'update' => [
                            'permission' => 'merchant.create',
                            'route' => route('users.registrations.edit', ['registration' => $data->id]),
                        ]
                    ];

                    $actions = array_merge($actions, $update);
                }

                return view('components.action', $actions)->render();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('status', function ($data) {
                return $data->status_label;
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', strtolower($keyword));
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Registration $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Registration $model)
    {
        return $model->when(!empty($this->request), function ($query) {
            $query->when(!empty($this->request->get('status')), function ($query) {
                $query->where('status', $this->request->get('status'));
            });
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
            ->setTableId('registration-table')
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
            Column::make('email')->title(__('labels.email')),
            Column::make('mobile_no')->title(__('labels.mobile_no')),
            Column::make('status')->title(__('labels.status')),
            Column::make('created_at')->title(__('labels.datetime')),
            Column::computed('action')
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
        return 'Registration_' . date('YmdHis');
    }
}
