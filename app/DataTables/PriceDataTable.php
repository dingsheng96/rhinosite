<?php

namespace App\DataTables;

use App\Models\Price;
use App\Models\Currency;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;

class PriceDataTable extends DataTable
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
            ->addColumn('currency', function ($data) {
                return $data->currency->name_with_code;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Price $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Price $model)
    {
        return $model->whereHasMorph('priceable', $this->parent_class, function (Builder $query) {
            $query->where('priceable_id', $this->parent_id);
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
            ->setTableId('price-table')
            ->addTableClass('table-hover table w-100')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
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
            Column::computed('DT_RowIndex', '#')->width('5%'),
            Column::make('currency')->title(__('labels.currency'))->width('20%'),
            Column::make('unit_price')->title(__('labels.unit_price'))->width('20%'),
            Column::make('discount')->title(__('labels.discount'))->width('20%'),
            Column::make('selling_price')->title(__('labels.selling_price'))->width('20%'),
            Column::make('created_at')->title(__('labels.created_at'))->width('15%')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Price_' . date('YmdHis');
    }
}
