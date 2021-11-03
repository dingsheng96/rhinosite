<?php

namespace App\DataTables\Merchant;

use App\Models\Project;
use App\Models\AdsBooster;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
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

                $options = [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'route' => route('merchant.ads-boosters.show', ['ads_booster' => base64_encode(json_encode(['boost_index' => $data->boost_index, 'boostable_type' => $data->boostable_type, 'boostable_id' => $data->boostable_id]))])
                    ],
                ];

                if (!$data->is_in_boosting_date_range) {
                    $options = array_merge($options, [
                        'delete' => [
                            'route' => route('merchant.ads-boosters.destroy', ['ads_booster' => base64_encode(json_encode(['boost_index' => $data->boost_index, 'boostable_type' => $data->boostable_type, 'boostable_id' => $data->boostable_id]))])
                        ]
                    ]);
                }

                return view('merchant.components.btn_action', $options)->render();
            })
            ->editColumn('title', function ($data) {

                if (!$data->boostable) {
                    return '-';
                }

                return  '<div class="d-flex justify-content-start">
                    <div>
                    <img src="' . $data->boostable->thumbnail->full_file_path . '" alt="' . $data->boostable->thumbnail->filename . '" class="table-img-preview">
                    </div>
                    <div class="pl-3">' . Str::limit($data->boostable->english_title, 50, '...') . '<br/>' . Str::limit($data->boostable->chinese_title ?? null, 50, '...') . '</div>
                    </div>';
            })
            ->editColumn('boosted_at', function ($data) {
                return $data->min_date . ' ~ ' . $data->max_date;
            })
            ->addColumn('status', function ($data) {
                return $data->boosting_date_range_status_label;
            })
            ->addColumn('ads_type', function ($data) {
                return $data->product->name;
            })
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereHasMorph('boostable', [Project::class], function (Builder $query) use ($keyword) {
                    $query->whereHas('translations', function ($query) use ($keyword) {
                        $query->where('value', 'like', "%{$keyword}%");
                    });
                });
            })
            ->filterColumn('boosted_at', function ($query, $keyword) {
                $query->selectRaw('DATE(MIN(boosted_at)) AS min_date, DATE(MAX(boosted_at)) AS max_date')
                    ->whereDate('min_date', 'like', "%{$keyword}%")
                    ->orWhereDate('max_date', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action', 'title', 'status'])
            ->order(function ($query) {
                $query->orderBy('max_date', 'desc');
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AdsBooster $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AdsBooster $model)
    {
        return $model->newQuery()
            ->with(['product', 'boostable'])
            ->whereHasMorph('boostable', [Project::class], function (Builder $query) {
                $query->where('user_id', Auth::id());
            })
            ->selectRaw('boost_index, DATE(MIN(boosted_at)) AS min_date, DATE(MAX(boosted_at)) AS max_date, product_id, boostable_type, boostable_id')
            ->groupBy('boost_index', 'product_id', 'boostable_type', 'boostable_id');
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
            // ->orderBy(5, 'desc')
            ->responsive(true)
            ->autoWidth(true)
            ->processing(false)
            ->searching(false);
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
            Column::make('title')->title(trans_choice('labels.project', 1)),
            Column::make('ads_type')->title(__('labels.ads_type')),
            Column::make('status')->title(__('labels.status'))->searchable(false),
            Column::make('boosted_at')->title(trans_choice('labels.boosted_at', 1)),
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
        return 'Ads_' . date('YmdHis');
    }
}
