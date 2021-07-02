<?php

namespace App\DataTables;

use App\Models\Project;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
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
                return view('components.action', [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'permission' => 'project.read',
                        'route' => route('projects.show', ['project' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'project.update',
                        'route' => route('projects.edit', ['project' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'project.delete',
                        'route' => route('projects.destroy', ['project' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('status', function ($data) {
                return '<h5>' . $data->status_label . '</h5>';
            })
            ->addColumn('merchant', function ($data) {
                return $data->user->name;
            })
            ->addColumn('price', function ($data) {
                return $data->price_with_unit;
            })
            ->editColumn('title', function ($data) {
                return $data->english_title . '<br/>' . $data->chinese_title;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->rawColumns(['action', 'status', 'title'])
            ->filterColumn('status', function ($query, $keyword) {
                $query->when($keyword == 'published', function ($query) {
                    $query->where('published', true);
                });
            })
            ->filterColumn('merchant', function ($query, $keyword) {
                $query->whereHas('user', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereHas('translations', function ($query) use ($keyword) {
                    $query->where('value', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('price', function ($query, $keyword) {
                $query->orWhere('unit_value', 'like', "%{$keyword}%")
                    ->orWhereHas('prices', function ($query) use ($keyword) {
                        $query->defaultPrice()->where('selling_price', 'like', "%{$keyword}%");
                    });
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Project $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Project $model)
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
            ->setTableId('project-table')
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
            Column::make('title')->title(__('labels.title')),
            Column::make('merchant')->title(__('labels.merchant')),
            Column::make('price')->title(__('labels.price') . ' / ' . __('labels.unit')),
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
        return 'Role_' . date('YmdHis');
    }
}
