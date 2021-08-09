<?php

namespace App\DataTables;

use App\Models\Project;
use App\Models\AdsBooster;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
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
                return $data->status_label;
            })
            ->editColumn('boosted_at', function ($data) {
                return $data->boosted_at->toDateTimeString();
            })
            ->filterColumn('ads_type', function ($query, $keyword) {
                return $query->whereHas('product', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('status', function ($query, $keyword) {

                $keyword = strtolower($keyword);

                return $query->when($keyword == 'expired', function ($query) {
                    $query->whereDate('boosted_at', '<', today());
                })->when($keyword == 'boosting', function ($query) {
                    $query->whereDate('boosted_at', today());
                })->when($keyword == 'upcoming', function ($query) {
                    $query->whereDate('boosted_at', '>', today());
                });
            })
            ->rawColumns(['status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Project $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AdsBooster $model)
    {
        return $model->with(['product', 'boostable'])
            ->whereHasMorph('boostable', [Project::class], function (Builder $query) {
                $query->where('id', $this->project->id)
                    ->when(Auth::user()->is_merchant, function ($query) {
                        $query->where('user_id', Auth::id());
                    });
            })
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
            Column::computed('DT_RowIndex', '#')->width('10%'),
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
