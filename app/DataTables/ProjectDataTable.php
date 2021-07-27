<?php

namespace App\DataTables;

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
                return '<span>' . $data->status_label . '</span>';
            })
            ->addColumn('merchant', function ($data) {
                return $data->user->name;
            })
            ->addColumn('price', function ($data) {
                return $data->price_with_unit;
            })
            ->editColumn('title', function ($data) {
                return  '<div class="row">
                <div class="col-12 col-xl-3">
                <img src="' . $data->thumbnail->full_file_path . '" alt="' . $data->thumbnail->filename . '" class="table-img-preview">
                </div>
                <div class="col-12 col-xl-9">' . $data->english_title . '<br/>' . $data->chinese_title . '</div>
                </div>';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', 'like', "%{$keyword}%");
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
        return $model->when(Auth::user()->is_merchant, function ($query) {
            $query->where('user_id', Auth::id());
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
            ->setTableId('project-table')
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
        $columns = [
            Column::computed('DT_RowIndex', '#')->width('5%'),
            Column::make('title')->title(__('labels.title'))->width('25%'),
            Column::make('merchant')->title(__('labels.merchant'))->width('20%'),
            Column::make('price')->title(__('labels.price') . ' / ' . __('labels.unit'))->width('15%'),
            Column::make('status')->title(__('labels.status'))->width('10%'),
            Column::make('created_at')->title(__('labels.created_at'))->width('15%'),
            Column::computed('action', __('labels.action'))->width('10%')
                ->exportable(false)
                ->printable(false),
        ];

        if (Auth::user()->is_merchant) {
            $columns = Arr::except($columns, 2);
        }

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
