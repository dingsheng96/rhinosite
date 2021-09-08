<?php

namespace App\DataTables\Admin;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MemberDataTable extends DataTable
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
                        'permission' => 'member.read',
                        'route' => route('members.show', ['member' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'member.update',
                        'route' => route('members.edit', ['member' => $data->id])
                    ],
                    'delete' => [
                        'permission' => 'member.delete',
                        'route' => route('members.destroy', ['member' => $data->id])
                    ]
                ])->render();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('phone', function ($data) {
                return $data->formatted_phone_number;
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
        return $model->member()->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('member-table')
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
            Column::computed('DT_RowIndex', '#')->width('5%'),
            Column::make('name')->title(__('labels.name'))->width('25%'),
            Column::make('email')->title(__('labels.email'))->width('20%'),
            Column::make('phone')->title(__('labels.contact_no'))->width('15%'),
            Column::make('status')->title(__('labels.status'))->width('10%'),
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
        return 'Member_' . date('YmdHis');
    }
}
