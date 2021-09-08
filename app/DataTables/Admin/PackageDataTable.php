<?php

namespace App\DataTables\Admin;

use App\Models\Package;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PackageDataTable extends DataTable
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
                        'permission' => 'package.read',
                        'route' => route('packages.show', ['package' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'package.update',
                        'route' => route('packages.edit', ['package' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'package.delete',
                        'route' => route('packages.destroy', ['package' => $data->id])
                    ]
                ])->render();
            })
            ->addColumn('status', function ($data) {
                return '<span>' . $data->status_label . '</span>';
            })
            ->addColumn('price', function ($data) {
                return $data->prices->first()->currency->code . ' ' . $data->prices->first()->selling_price;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->toDateTimeString();
            })
            ->editColumn('quantity', function ($data) {
                return $data->stock_type == Package::STOCK_TYPE_INFINITE ? '<span>&infin;</span>' : $data->quantity;
            })
            ->filterColumn('status', function ($query, $keyword) {
                return $query->where('status', strtolower($keyword));
            })
            ->filterColumn('category', function ($query, $keyword) {
                return $query->where(app(ProductCategory::class)->getTable() . '.name', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action', 'status', 'quantity']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Package $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Package $model)
    {
        return $model->withCount(['products'])
            ->with(['prices' => function ($query) {
                $query->defaultPrice();
            }])
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
            ->setTableId('package-table')
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
            Column::make('name')->title(__('labels.name'))->width('25%'),
            Column::make('products_count')->title(trans_choice('labels.item', 2))->width('10%'),
            Column::make('price')->title(__('labels.price'))->width('15%'),
            Column::make('quantity')->title(__('labels.quantity'))->width('10%'),
            Column::make('status')->title(__('labels.status'))->width('10%'),
            Column::make('created_at')->title(__('labels.created_at'))->width('15%'),
            Column::computed('action', __('labels.action'))->width('10%')
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
