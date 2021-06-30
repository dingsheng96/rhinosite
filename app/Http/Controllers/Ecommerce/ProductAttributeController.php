<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\Price;
use App\Models\Product;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\DataTables\PriceDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Facades\PriceFacade;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Ecommerce\ProductPriceRequest;
use App\Http\Requests\Ecommerce\ProductAttributeRequest;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, ProductAttribute $attribute, PriceDataTable $dataTable)
    {
        return $dataTable->with([
            'parent_class' => ProductAttribute::class,
            'parent_id' => $attribute->id,
            'delete_permission' => 'product.delete',
            'update_permission' => 'product.update',
            'delete_route' => route('ecommerce.products.attributes.prices.destroy', ['product' => $product->id, 'attribute' => $attribute->id, 'price' => '__REPLACE__'])
        ])->render('ecommerce.product.attribute.show', compact('product', 'attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, ProductAttribute $attribute, PriceDataTable $dataTable)
    {
        $stock_types = [
            ProductAttribute::STOCK_TYPE_FINITE => __('labels.finite'),
            ProductAttribute::STOCK_TYPE_INFINITE => __('labels.infinite')
        ];

        return $dataTable->with([
            'parent_class' => ProductAttribute::class,
            'parent_id' => $attribute->id,
            'delete_permission' => 'product.delete',
            'update_permission' => 'product.update',
            'delete_route' => route('ecommerce.products.attributes.prices.destroy', ['product' => $product->id, 'attribute' => $attribute->id, 'price' => '__REPLACE__'])
        ])->render('ecommerce.product.attribute.edit', compact('product', 'attribute', 'stock_types'));
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
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $attribute->sku             = $request->get('sku');
            $attribute->stock_type      = $request->get('stock_type');
            $attribute->quantity        = $request->get('quantity');
            $attribute->is_available    = $request->has('is_available');
            $attribute->validity        = $request->get('validity');
            $attribute->color           = $request->get('color');

            if ($attribute->isDirty()) {
                $attribute->save();
            }

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('ecommerce.products.edit', ['product' => $product->id])
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
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

            $attribute->prices()->delete();
            $attribute->delete();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.product', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('ecommerce.products.edit', ['product' => $product->id])
            ])
            ->sendJson();
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storePrice(ProductPriceRequest $request, Product $product, ProductAttribute $attribute)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(__('labels.price'));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            [
                'unit_price' => $unit_price,
                'discount' => $discount,
                'currency' => $currency
            ] = $request->get('create');

            $price = new Price();
            $price->currency_id         =   $currency;
            $price->unit_price          =   $unit_price;
            $price->discount            =   $discount ?? 0;
            $price->discount_percentage =   PriceFacade::calcDiscountPercentage($discount ?? 0, $unit_price);
            $price->selling_price       =   $unit_price - $discount;

            $attribute->prices()->save($price);

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($price)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('ecommerce.products.attributes.edit', ['product' => $product->id, 'attribute' => $attribute->id])->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new Price())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->with($status, $message)->withInput();
        }
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePrice(ProductPriceRequest $request, Product $product, ProductAttribute $attribute, Price $price)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(__('labels.price'));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            [
                'unit_price' => $unit_price,
                'discount' => $discount,
                'currency' => $currency
            ] = $request->get('update');

            $price->currency_id         =   $currency;
            $price->unit_price          =   $unit_price;
            $price->discount            =   $discount ?? 0;
            $price->discount_percentage =   PriceFacade::calcDiscountPercentage($discount ?? 0, $unit_price);
            $price->selling_price       =   $unit_price - $discount;

            if ($price->isDirty()) {
                $price->save();
            }

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($price)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('ecommerce.products.attributes.edit', ['product' => $product->id, 'attribute' => $attribute->id])->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($price)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->with($status, $message)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePrice(Product $product, ProductAttribute $attribute, Price $price)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.price', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $price->delete();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($price)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($price)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.product', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('ecommerce.products.attributes.edit', ['product' => $product->id, 'attribute' => $attribute->id])
            ])
            ->sendJson();
    }
}
