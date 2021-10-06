<?php

namespace App\DataTables\Admin;

use App\Models\User;
use App\Models\Package;
use App\Models\UserDetail;
use Illuminate\Support\Arr;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\UserSubscriptionLog;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionLogsDataTable extends DataTable
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
            ->addColumn('plan', function ($data) {
                return $data->name ?? $data->product->name;
            })
            ->addColumn('expired_at', function ($data) {
                return $data->userSubscriptionLogs->first()->expired_at ?? '-';
            })
            ->addColumn('renewed_at', function ($data) {
                return $data->userSubscriptionLogs->first()->renewed_at ?? '-';
            })
            ->editColumn('status', function ($data) {
                return $data->status_label;
            })
            ->filterColumn('plan', function ($query, $keyword) {
                return $query->whereHasMorph(
                    'subscribable',
                    [Package::class, ProductAttribute::class],
                    function (Builder $query, $type) use ($keyword) {
                        if ($type == ProductAttribute::class) {
                            $query->whereHas('product', function ($query) use ($keyword) {
                                $query->where('name', 'like', "%{$keyword}%");
                            });
                        } elseif ($type == Package::class) {
                            $query->where('name', 'like', "%{$keyword}%");
                        }
                    }
                );
            })
            ->filterColumn('renewed_at', function ($query, $keyword) {
                return $query->whereHas('userSubscriptionLogs', function ($query) use ($keyword) {
                    $query->where('renewed_at', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('expired_at', function ($query, $keyword) {
                return $query->whereHas('userSubscriptionLogs', function ($query) use ($keyword) {
                    $query->where('expired_at', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\UserSubscription $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserSubscription $model)
    {
        return $model->with([
            'userSubscriptionLogs' => function ($query) {
                $query->latest('renewed_at')->limit(1);
            }
        ])->where('user_id', $this->merchant->id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('subscription-log-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy('3', 'desc')
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
            Column::make('plan')->title(__('labels.plan')),
            Column::make('status')->title(__('labels.status')),
            Column::make('renewed_at')->title(trans_choice('labels.renewed_at', 1)),
            Column::make('expired_at')->title(__('labels.valid_till')),
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
        return 'UserSubscriptionLog_' . date('YmdHis');
    }
}
