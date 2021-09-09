<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Misc;
use App\Helpers\Status;
use App\Models\Product;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\ProductAttributeFacade;
use App\Http\Requests\Admin\ProductAttributeRequest;

class ProductAttributeController extends Controller
{
    public $stock_types, $statuses, $slot_types, $validity_types;

    public function __construct()
    {
        $this->stock_types      =   Misc::instance()->productStockTypes();
        $this->validity_types   =   Misc::instance()->validityType();
        $this->statuses         =   Status::instance()->activeStatus();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
        return view('admin.product.attribute.create', [
            'product' => $product,
            'stock_types' => $this->stock_types,
            'statuses' => $this->statuses,
            'validity_types' => $this->validity_types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductAttributeRequest $request, Product $product)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('labels.attribute', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $attribute = ProductAttributeFacade::setParent($product)
                ->setRequest($request)->storeData()->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:product_attribute')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.products.edit', ['product' => $product->id])->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:product_attribute')
                ->causedBy(Auth::user())
                ->performedOn(new ProductAttribute())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->with('fail', $message)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, ProductAttribute $attribute)
    {
        $attribute->load([
            'prices' => function ($query) {
                $query->with(['currency.countries'])->defaultPrice();
            }
        ]);

        $default_price = $attribute->prices->first();

        return view('admin.product.attribute.show', compact('product', 'attribute', 'default_price'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, ProductAttribute $attribute)
    {
        $attribute->load([
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ]);

        $default_price = $attribute->prices->first();

        return view('admin.product.attribute.edit', [
            'product' => $product,
            'attribute' => $attribute,
            'statuses' => $this->statuses,
            'stock_types' => $this->stock_types,
            'default_price' => $default_price,
            'slot_types' => $this->slot_types,
            'validity_types' => $this->validity_types
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductAttributeRequest $request, Product $product, ProductAttribute $attribute)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('labels.attribute', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $product->load(['productAttributes.prices']);

            $attribute = ProductAttributeFacade::setParent($product)
                ->setModel($attribute)->setRequest($request)
                ->storeData()->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:product_attribute')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.products.edit', ['product' => $product->id])->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:product_attribute')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->with('fail', $message)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, ProductAttribute $attribute)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.attribute', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $attribute->loadCount([
                'userSubscriptions' => function ($query) {
                    $query->active();
                },
                'package' =>  function ($query) {
                    $query->whereHas('userSubscriptions', function ($query) {
                        $query->active();
                    });
                }
            ]);

            throw_if(
                $attribute->user_subscriptions_count > 0 || $attribute->package_count > 0,
                new \Exception(__('messages.in_using', ['item' => $module]))
            );

            $attribute->delete($attribute);

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:product_attribute')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:product_attribute')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->log($e->getMessage());

            $message = $e->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.product', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.products.edit', ['product' => $product->id])
            ])
            ->sendJson();
    }
}
