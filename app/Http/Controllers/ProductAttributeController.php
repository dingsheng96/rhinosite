<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Helpers\Status;
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
use App\Support\Facades\ProductAttributeFacade;
use App\Http\Requests\Ecommerce\ProductPriceRequest;
use App\Http\Requests\Ecommerce\ProductAttributeRequest;

class ProductAttributeController extends Controller
{
    public $stock_types, $statuses;

    public function __construct()
    {
        $this->stock_types = [ProductAttribute::STOCK_TYPE_FINITE, ProductAttribute::STOCK_TYPE_INFINITE];
        $this->statuses = Status::instance()->activeStatus();
    }

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
    public function create(Product $product)
    {
        return view('product.attribute.create', [
            'product' => $product,
            'stock_types' => $this->stock_types,
            'statuses' => $this->statuses
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
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $attribute = ProductAttributeFacade::setParentModel($product)
                ->setRequest($request)
                ->storeData()
                ->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('products.edit', ['product' => $product->id])
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
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
    public function show(Product $product, ProductAttribute $attribute, PriceDataTable $dataTable)
    {
        return $dataTable->with([
            'parent_class' => ProductAttribute::class,
            'parent_id' => $attribute->id,
            'delete_permission' => 'product.delete',
            'update_permission' => 'product.update',
            'delete_route' => route('products.attributes.prices.destroy', ['product' => $product->id, 'attribute' => $attribute->id, 'price' => '__REPLACE__'])
        ])->render('product.attribute.show', compact('product', 'attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, ProductAttribute $attribute, PriceDataTable $dataTable)
    {
        $default_price = $attribute->prices()->defaultPrice()->first();

        return $dataTable->with([
            'parent_class' => ProductAttribute::class,
            'parent_id' => $attribute->id,
        ])->render('product.attribute.edit', [
            'product' => $product,
            'attribute' => $attribute,
            'statuses' => $this->statuses,
            'stock_types' => $this->stock_types,
            'default_price' => $default_price
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
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $attribute = ProductAttributeFacade::setModel($attribute)
                ->setRequest($request)
                ->storeData()
                ->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('products.edit', ['product' => $product->id])
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
                'redirect_to' => route('products.edit', ['product' => $product->id])
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

            return redirect()->route('products.attributes.edit', ['product' => $product->id, 'attribute' => $attribute->id])->withSuccess($message);
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

            return redirect()->route('products.attributes.edit', ['product' => $product->id, 'attribute' => $attribute->id])->withSuccess($message);
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
                'redirect_to' => route('products.attributes.edit', ['product' => $product->id, 'attribute' => $attribute->id])
            ])
            ->sendJson();
    }
}
