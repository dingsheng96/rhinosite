<?php

namespace App\DataTables;

use App\Models\AdsBooster;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

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
                        'permission' => 'merchant.create',
                        'route' => route('verifications.show', ['verification' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'merchant.create',
                        'route' => route('verifications.edit', ['verification' => $data->id]),
                    ]
                ])->render();
            })
            ->addColumn('project', function ($data) {
                return  '<div class="row">
                <div class="col-6 col-md-3">
                <img src="' . $data->boostable->thumbnail->full_file_path . '" alt="' . $data->boostable->thumbnail->filename . '" class="table-img-preview">
                </div>
                <div class="col-6 col-md-9">' . $data->boostable->english_title . '<br/>' . $data->boostable->chinese_title . '</div>
                </div>';
            })
            ->addColumn('merchant', function ($data) {
                return $data->boostable->user->name;
            })
            ->addColumn('ads_type', function ($data) {
                return $data->productAttribute->product->name;
            })
            ->addColumn('status', function ($data) {
                return $data->status_label;
            })
            ->editColumn('boosted_at', function ($data) {
                return $data->boosted_at->toDateTimeString();
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            // ->filterColumn('name', function ($query, $keyword) {
            //     $query->whereHas('user', function ($query) use ($keyword) {
            //         $query->where('name', 'like', "%{$keyword}%");
            //     });
            // })
            ->rawColumns(['action', 'project', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AdsBooster $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AdsBooster $model)
    {
        return $model->with([
            'productAttribute.product',
            'boostable' => function ($query) {
                $query->when(Auth::user()->is_merchant, function ($query) {
                    $query->where('user_id', Auth::id());
                });
            }
        ])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $order_index = Auth::user()->is_merchant ? 4 : 5;

        return $this->builder()
            ->setTableId('ads-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy($order_index, 'desc')
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
            Column::make('project')->title(trans_choice('labels.project', 1)),
            Column::make('merchant')->title(__('labels.merchant'))->width('15%'),
            Column::make('ads_type')->title(__('labels.ads_type'))->width('15%'),
            Column::make('status')->title(__('labels.status'))->width('10%'),
            Column::make('boosted_at')->title(trans_choice('labels.boosted_at', 1))->width('15%'),
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
        return 'Ads_' . date('YmdHis');
    }
}
