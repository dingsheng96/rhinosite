<?php

namespace App\DataTables;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductAttributeDataTable extends DataTable
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
                        'permission' => 'product.read',
                        'route' => route('ecommerce.products.attributes.show', ['product' => $this->product_id, 'attribute' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'product.update',
                        'route' => route('ecommerce.products.attributes.edit', ['product' => $this->product_id, 'attribute' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'product.delete',
                        'route' => route('ecommerce.products.attributes.destroy', ['product' => $this->product_id, 'attribute' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('status', function ($data) {
                return '<h5>' . $data->status_label . '</h5>';
            })
            ->editColumn('quantity', function ($data) {
                return $data->stock_type == ProductAttribute::STOCK_TYPE_INFINITE ? '<h4>&infin;</h4>' : $data->quantity;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->filterColumn('status', function ($query, $keyword) {
                return $query->when($keyword == 'available', function ($query) {
                    $query->where('is_available', true);
                })->when($keyword == 'unavailable', function ($query) {
                    $query->where('is_available', false);
                });
            })
            ->rawColumns(['action', 'status', 'quantity']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProductAttribute $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProductAttribute $model)
    {
        return $model->where('product_id', $this->product_id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('product-attribute-table')
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
            Column::make('sku')->title(__('labels.sku')),
            Column::make('quantity')->title(__('labels.quantity')),
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
        return 'ProductAttribute_' . date('YmdHis');
    }
}
