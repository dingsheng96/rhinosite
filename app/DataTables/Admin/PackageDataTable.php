<?php

namespace App\DataTables\Admin;

use App\Models\Package;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Support\Facades\PriceFacade;
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
                return view('admin.components.btn_action', [
                    'no_action' => $this->no_action ?: null,
                    'view' => [
                        'permission' => 'package.read',
                        'route' => route('admin.packages.show', ['package' => $data->id])
                    ],
                    'update' => [
                        'permission' => 'package.update',
                        'route' => route('admin.packages.edit', ['package' => $data->id]),
                    ],
                    'delete' => [
                        'permission' => 'package.delete',
                        'route' => route('admin.packages.destroy', ['package' => $data->id])
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
            ->filterColumn('status', function ($query, $keyword) {
                return $query->where('status', strtolower($keyword));
            })
            ->filterColumn('category', function ($query, $keyword) {
                return $query->where(app(ProductCategory::class)->getTable() . '.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('price', function ($query, $keyword) {
                return $query->whereHas('prices', function ($query) use ($keyword) {
                    return $query->where('selling_price', 'like', PriceFacade::convertFloatToInt($keyword) . '%');
                });
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
            Column::computed('DT_RowIndex', '#'),
            Column::make('name')->title(__('labels.name')),
            Column::make('price')->title(__('labels.price')),
            Column::make('products_count')->title(__('labels.package_include_items'))->searchable(false),
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
        return 'Package_' . date('YmdHis');
    }
}
