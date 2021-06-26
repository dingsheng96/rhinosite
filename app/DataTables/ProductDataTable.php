<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
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
                        'permission' => 'product.create',
                        'route' => route('ecommerce.products.show', ['product' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'product.create',
                        'route' => route('ecommerce.products.edit', ['product' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'product.delete',
                        'route' => route('ecommerce.products.destroy', ['product' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('status', function ($data) {
                return '<h5>' . $data->status_label . '</h5>';
            })
            ->addColumn('price_from', function ($data) {
                return $data->currency->code . $data->lowest_selling_price . ' ++';
            })
            ->addColumn('variation', function ($data) {
                return $data->product_attributes_count ?? 0;
            })
            ->addColumn('type', function ($data) {
                return $data->productType->name;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->filterColumn('status', function ($query, $keyword) {
                return $query->when(strtolower($keyword) == 'available', function ($query) {
                    $query->where('is_available', true);
                })->when(strtolower($keyword) == 'unavailable', function ($query) {
                    $query->where('is_available', false);
                });
            })
            ->filterColumn('type', function ($query, $keyword) {
                return $query->productTypeChecker($keyword);
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        return $model->with(['productPrices'])
            ->withCount(['productAttributes'])
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
            ->setTableId('product-table')
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
            Column::make('name')->title(__('labels.name')),
            Column::make('type')->title(__('labels.type')),
            Column::make('price_from')->title(__('labels.price_from')),
            Column::make('variation')->title(trans_choice('labels.variation', 2)),
            Column::make('status')->title(__('labels.status')),
            Column::make('created_at')->title(__('labels.datetime')),
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
        return 'Product_' . date('YmdHis');
    }
}
