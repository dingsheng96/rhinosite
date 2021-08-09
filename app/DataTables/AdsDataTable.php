<?php

namespace App\DataTables;

use App\Models\Project;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;

class AdsDataTable extends DataTable
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
                        'permission' => 'ads.create',
                        'route' => route('ads-boosters.show', ['ads_booster' => $data->id])
                    ]
                ])->render();
            })
            ->editColumn('title', function ($data) {

                return  '<div class="d-flex justify-content-start">
                <div>
                <img src="' . $data->thumbnail->full_file_path . '" alt="' . $data->thumbnail->filename . '" class="table-img-preview">
                </div>
                <div class="pl-3">' . Str::limit($data->english_title, 50, '...') . '<br/>' . Str::limit($data->chinese_title ?? null, 50, '...') . '</div>
                </div>';
            })
            ->addColumn('merchant', function ($data) {
                return $data->user->name;
            })
            ->addColumn('status', function ($data) {
                return $data->boosting_status;
            })
            ->filterColumn('merchant', function ($query, $keyword) {
                $query->whereHasMorph('boostable', [Project::class], function (Builder $query) use ($keyword) {
                    $query->whereHas('user', function ($query) use ($keyword) {
                        $query->merchant()->where('name', 'like', "%{$keyword}%");
                    });
                });
            })
            ->rawColumns(['action', 'title', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Project $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Project $model)
    {
        return $model->with(['adsBoosters.product', 'media', 'translations'])
            ->when(Auth::user()->is_merchant, function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('adsBoosters')
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
            ->setTableId('ads-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2, 'desc')
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
            Column::make('title')->title(trans_choice('labels.project', 1))->width('35%'),
            Column::make('status')->title(__('labels.status'))->width('20%'),
            Column::make('merchant')->title(__('labels.merchant'))->width('30%'),
            Column::computed('action', __('labels.action'))->width('10%')
                ->exportable(false)
                ->printable(false),
        ];

        if (Auth::user()->is_merchant) {
            $columns = Arr::except($columns, 3);
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
        return 'Ads_' . date('YmdHis');
    }
}
