<?php

namespace App\Http\Controllers;

use App\Helpers\Status;
use App\Models\Product;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
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
        $this->statuses   = Status::instance()->activeStatus();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('product.index');
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

        return view('product.create', compact('categories', 'max_files', 'statuses', 'stock_types'));
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
        $module     =   strtolower(trans_choice('modules.product', 1));
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
                    'redirect_to' => route('products.index')
                ])
                ->sendJson()
                : redirect()->route('products.index')->withSuccess($message);
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
    public function show(Product $product, ProductAttributeDataTable $dataTable)
    {
        $attributes     =   $product->productAttributes()->get();
        $thumbnail      =   $product->media()->thumbnail()->first();
        $images         =   $product->media()->image()->get();

        return $dataTable->with(['product_id' => $product->id])
            ->render('product.show', compact('product', 'attributes', 'thumbnail', 'images'));
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
                'product.edit',
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
    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.product', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $product = ProductFacade::setModel($product)->setRequest($request)->storeData()->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

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
                    'redirect_to' => route('products.index')
                ])
                ->sendJson()
                : redirect()->route('products.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return ($request->ajax())
                ? Response::instance()
                ->withStatusCode('modules.product', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->sendJson()
                : redirect()->back()->with('fail', $message)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.product', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $product->delete($product);

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.product', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('products.index')
            ])
            ->sendJson();
    }
}
