<?php

namespace App\DataTables\Admin;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminDataTable extends DataTable
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
                    'no_action' => $this->no_action ?: $data->id == Auth::id(),
                    'view' => [
                        'permission' => 'admin.read',
                        'route' => route('admin.admins.show', ['admin' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'admin.update',
                        'route' => route('admin.admins.edit', ['admin' => $data->id])
                    ],
                    'delete' => [
                        'permission' => 'admin.delete',
                        'route' => route('admin.admins.destroy', ['admin' => $data->id])
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('last_login_at', function ($data) {
                return optional($data->last_login_at)->toDateTimeString();
            })
            ->editColumn('status', function ($data) {
                return '<span>' . $data->status_label . '</span>';
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', strtolower($keyword));
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->admin()->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('admin-table')
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
            Column::computed('DT_RowIndex', '#'),
            Column::make('name')->title(__('labels.name')),
            Column::make('email')->title(__('labels.email')),
            Column::make('status')->title(__('labels.status')),
            Column::make('last_login_at')->title(__('labels.last_login_at')),
            Column::make('created_at')->title(__('labels.created_at')),
            Column::computed('action', __('labels.action'))
                ->exportable(false)
                ->printable(false)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Admin_' . date('YmdHis');
    }
}
