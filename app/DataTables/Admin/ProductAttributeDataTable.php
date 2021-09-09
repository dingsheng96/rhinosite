<?php

namespace App\DataTables\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Support\Facades\PriceFacade;
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
                        'route' => route('admin.products.attributes.show', ['product' => $this->product->id, 'attribute' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'product.update',
                        'route' => route('admin.products.attributes.edit', ['product' => $this->product->id, 'attribute' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'product.delete',
                        'route' => route('admin.products.attributes.destroy', ['product' => $this->product->id, 'attribute' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('price', function ($data) {
                return $data->prices->first()->selling_price_with_currency;
            })
            ->addColumn('status', function ($data) {
                return $data->status_label . ($data->published ? '<br> <span class="badge badge-pill badge-primary px-3">' . __('labels.published') . '</span>' : '');
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('slot', function ($data) {
                return $data->slot_with_type;
            })
            ->filterColumn('status', function ($query, $keyword) {

                $keyword = strtolower($keyword);

                return $query->when($keyword == 'published', function ($query) {
                    $query->where('published', true);
                })->orWhere('status', 'like', "%{$keyword}%");
            })
            ->filterColumn('price', function ($query, $keyword) {
                return $query->whereHas('prices', function ($query) use ($keyword) {
                    $query->defaultPrice()->where('selling_price', 'like', PriceFacade::convertIntToFloat($keyword) . '%');
                });
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProductAttribute $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProductAttribute $model)
    {
        return $model->with([
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ])->where('product_id', $this->product->id)->newQuery();
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
            Column::computed('DT_RowIndex', '#'),
            Column::make('sku')->title(__('labels.sku')),
            Column::make('quantity')->title(__('labels.variation_quantity')),
            Column::make('price')->title(__('labels.price')),
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
