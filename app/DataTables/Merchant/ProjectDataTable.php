<?php

namespace App\DataTables\Merchant;

use App\Models\Project;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProjectDataTable extends DataTable
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
                return view('merchant.components.btn_action', [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'route' => route('app.project.show', ['project' => $data->id]),
                        'attribute' => 'target="_blank"'
                    ],
                    'update' => [
                        'route' => route('merchant.projects.edit', ['project' => $data->id]),
                    ],
                    'delete' => [
                        'route' => route('merchant.projects.destroy', ['project' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('status', function ($data) {
                return '<span>' . $data->status_label . '</span>';
            })
            ->editColumn('title', function ($data) {
                return  '<div class="d-flex justify-content-start">
                <div>
                <img src="' . $data->thumbnail->full_file_path . '" alt="' . $data->thumbnail->filename . '" class="table-img-preview">
                </div>
                <div class="pl-3">' . $data->english_title . '<br/>' . $data->chinese_title . '</div>
                </div>';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', 'like', "%{$keyword}%");
            })
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereHas('translations', function ($query) use ($keyword) {
                    $query->where('value', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action', 'status', 'title']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Project $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Project $model)
    {
        return $model->where('user_id', Auth::id())->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('project-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(4, 'desc')
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
        $columns = [
            Column::computed('DT_RowIndex', '#'),
            Column::make('title')->title(__('labels.title')),
            Column::make('status')->title(__('labels.status')),
            Column::make('created_at')->title(__('labels.created_at')),
            Column::computed('action', __('labels.action'))
                ->exportable(false)
                ->printable(false),
        ];

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Project_' . date('YmdHis');
    }
}
