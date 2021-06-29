<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\Status;
use App\Models\Product;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\ProductFacade;
use App\DataTables\ProductAttributeDataTable;
use App\Http\Requests\Ecommerce\ProductRequest;

class ProductController extends Controller
{
    public $stock_types, $categories, $statuses;

    public function __construct()
    {
        $this->stock_types = [
            ProductAttribute::STOCK_TYPE_FINITE => __('labels.finite'),
            ProductAttribute::STOCK_TYPE_INFINITE => __('labels.infinite')
        ];

        $this->categories = ProductCategory::orderBy('name', 'asc')->get();
        $this->statuses   = Status::instance()->productStatus();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('ecommerce.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $max_files      =   Product::MAX_IMAGES;
        $statuses       =   $this->statuses;
        $stock_types    =   $this->stock_types;
        $categories     =   $this->categories;

        return view('ecommerce.product.create', compact('categories', 'max_files', 'statuses', 'stock_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.submodules.product', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $product = ProductFacade::setRequest($request)->storeData()->getModel();

            DB::commit();

            $message =  Message::instance()->format($action, $module, 'success');
            $status  =  'success';

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->withProperties($request->all())
                ->log($message);

            return ($request->ajax())
                ? Response::instance()
                ->withStatusCode('modules.product', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->withData([
                    'redirect_to' => route('ecommerce.products.index')
                ])
                ->sendJson()
                : redirect()->route('ecommerce.products.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new Product())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return Response::instance()
                ->withStatusCode('modules.product', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->sendJson();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, ProductAttributeDataTable $dataTable)
    {
        $attributes     =   $product->productAttributes()->get();
        $thumbnail      =   $product->media()->thumbnail()->first();
        $images         =   $product->media()->image()->get();
        $max_files      =   Product::MAX_IMAGES - $images->count();
        $statuses       =   $this->statuses;
        $stock_types    =   $this->stock_types;
        $categories     =   $this->categories;

        return $dataTable->with(['product_id' => $product->id])
            ->render(
                'ecommerce.product.edit',
                compact('product', 'max_files', 'statuses', 'stock_types', 'categories', 'thumbnail', 'images', 'attributes')
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
