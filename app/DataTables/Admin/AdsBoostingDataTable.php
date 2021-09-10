<?php

namespace App\DataTables\Admin;

use App\Models\Project;
use App\Models\AdsBooster;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;

class AdsBoostingDataTable extends DataTable
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
            ->addColumn('ads_type', function ($data) {
                return $data->product->name;
            })
            ->addColumn('status', function ($data) {
                return $data->boosting_date_range_status_label;
            })
            ->editColumn('boosted_at', function ($data) {
                return $data->min_date . ' ~ ' . $data->max_date;
            })
            ->rawColumns(['status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AdsBooster $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AdsBooster $model)
    {
        return $model->with(['product', 'boostable'])
            ->whereHasMorph('boostable', [Project::class], function (Builder $query) {
                $query->where('id', $this->project->id);
            })
            ->selectRaw('boost_index, DATE(MIN(boosted_at)) AS min_date, DATE(MAX(boosted_at)) AS max_date, product_id')
            ->groupBy('boost_index', 'product_id')
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
            ->setTableId('ads-boosting-history-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(3, 'desc')
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
        return [
            Column::computed('DT_RowIndex', '#'),
            Column::make('ads_type')->title(__('labels.ads_type')),
            Column::make('status')->title(__('labels.status')),
            Column::make('boosted_at')->title(trans_choice('labels.boosted_at', 1)),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'AdsBoostingHistory_' . date('YmdHis');
    }
}
