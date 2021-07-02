<?php

namespace App\DataTables;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ActivityLogDataTable extends DataTable
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
            ->addColumn('caused_by', function ($data) {
                $model  =   $data->causer_type;
                $id     =   $data->causer_id;

                return '<span>Caused Model: ' . $model . '<br/>Caused_ID: ' . $id . '</span>';
            })
            ->addColumn('subject_to', function ($data) {
                $model  =   $data->subject_type;
                $id     =   $data->subject_id;

                return '<span>Subject Model: ' . $model . '<br/>Subject_ID: ' . $id . '</span>';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('description', function ($data) {
                return Str::limit($data->description);
            })
            ->rawColumns(['action', 'caused_by', 'subject_to']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Spatie\Activitylog\Models\Activity $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Activity $model)
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
            ->setTableId('activity-log-table')
            ->addTableClass('table-hover table-bordered table-head-fixed table-striped')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5, 'desc')
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
            Column::make('log_name')->title(__('labels.log_name')),
            Column::make('subject_to')->title(__('labels.subject_to')),
            Column::make('caused_by')->title(__('labels.caused_by')),
            Column::make('description')->title(__('labels.description')),
            Column::make('created_at')->title(__('labels.created_at'))
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ActivityLog_' . date('YmdHis');
    }
}
