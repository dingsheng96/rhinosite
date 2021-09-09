<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Misc;
use App\Models\Media;
use App\Helpers\Status;
use App\Models\Product;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\ProductFacade;
use App\DataTables\Admin\ProductDataTable;
use App\Http\Requests\Admin\ProductRequest;
use App\DataTables\Admin\ProductAttributeDataTable;

class ProductController extends Controller
{
    public $stock_types, $categories, $statuses;

    public function __construct()
    {
        $this->stock_types = [
            ProductAttribute::STOCK_TYPE_FINITE => __('labels.finite'),
            ProductAttribute::STOCK_TYPE_INFINITE => __('labels.infinite')
        ];
        $this->slot_types =   Misc::instance()->adsSlotType();
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
        if (Auth::user()->is_merchant) {
            return $this->purchaseIndex();
        }

        return $dataTable->render('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.create', [
            'statuses' => $this->statuses,
            'stock_types' => $this->stock_types,
            'categories' => $this->categories,
            'slot_types' => $this->slot_types
        ]);
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

            return ($request->ajax())
                ? Response::instance()
                ->withStatusCode('modules.product', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->sendJson()
                : redirect()->back()->with('fail', $message);
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
        $product->load([
            'productAttributes',
            'media' => function ($query) {
                $query->thumbnail()
                    ->orWhere(function ($query) {
                        $query->image();
                    });
            }
        ]);

        $attributes     =   $product->productAttributes;
        $thumbnail      =   $product->thumbnail;
        $images         =   $product->images;
        $no_action      =   true;

        return $dataTable->with(compact('product', 'no_action'))->render('admin.product.show', compact('product', 'attributes', 'thumbnail', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, ProductAttributeDataTable $dataTable)
    {
        $product->load([
            'productAttributes', 'productCategory',
            'media' => function ($query) {
                $query->thumbnail()
                    ->orWhere(function ($query) {
                        $query->image();
                    });
            }
        ]);

        $statuses       =   $this->statuses;
        $stock_types    =   $this->stock_types;
        $categories     =   $this->categories;
        $slot_types     =   $this->slot_types;

        return $dataTable->with(compact('product'))->render('admin.product.edit', compact('product', 'statuses', 'stock_types', 'categories', 'slot_types'));
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
                : redirect()->route('admin.products.index')->withSuccess($message);
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
        DB::beginTransaction();

        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.product', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $product->loadCount([
                'productAttributes' => function ($query) {
                    $query->whereHas('userSubscriptions', function ($query) {
                        $query->active();
                    })->orWhereHas('package', function ($query) {
                        $query->whereHas('userSubscriptions', function ($query) {
                            $query->active();
                        });
                    });
                },
                'userAdsQuotas',
            ]);

            throw_if(
                $product->product_attributes_count > 0 || $product->user_ads_quotas_count > 0,
                new \Exception(__('messages.in_using', ['item' => $module]))
            );

            $product->delete($product);

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();

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

            $message = $e->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.product', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.products.index')
            ])
            ->sendJson();
    }

    public function purchaseIndex()
    {
        $products = Product::with([
            'productCategory',
            'productAttributes' => function ($query) {
                $query->with(['prices' => function ($query) {
                    $query->defaultPrice();
                }])->active()->published();
            }
        ])->filterCategory(ProductCategory::TYPE_ADS)
            ->whereHas('productAttributes', function ($query) {
                $query->active()->published();
            })->orderBy('name')
            ->get();

        return view('product.purchase', compact('products'));
    }
}
