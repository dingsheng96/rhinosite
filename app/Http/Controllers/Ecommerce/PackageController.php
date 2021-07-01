<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\Status;
use App\Models\Package;
use App\Models\Product;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\DataTables\PackageDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\PackageFacade;
use App\Http\Requests\Ecommerce\PackageRequest;

class PackageController extends Controller
{
    public $products, $stock_types, $statuses;

    public function __construct()
    {
        $this->products     =   Product::orderBy('name', 'asc')->get();
        $this->stock_types  =   [Package::STOCK_TYPE_FINITE, Package::STOCK_TYPE_INFINITE];
        $this->statuses     =   Status::instance()->packageStatus();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PackageDataTable $dataTable)
    {
        return $dataTable->render('ecommerce.package.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ecommerce.package.create', [
            'products' => $this->products,
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
    public function store(PackageRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.submodules.package', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $package = PackageFacade::setRequest($request)->storeData()->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($package)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('ecommerce.packages.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new Package())
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
    public function edit(Package $package)
    {
        $price = $package->prices()->defaultPrice()->first();

        $items = $package->products()->get();

        return view('ecommerce.package.edit', [
            'package' => $package,
            'price' => $price,
            'items' => $items,
            'products' => $this->products,
            'stock_types' => $this->stock_types,
            'statuses' => $this->statuses
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PackageRequest $request, Package $package)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.submodules.package', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $package = PackageFacade::setModel($package)->setRequest($request)->storeData()->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($package)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('ecommerce.packages.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($package)
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
    public function destroy(Package $package)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.submodules.package', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $package->prices()->delete();
            $package->delete();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($package)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($package)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.package', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('ecommerce.packages.edit', ['package' => $package->id])
            ])
            ->sendJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePackageProduct(Package $package, ProductAttribute $product)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.submodules.package', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $package->products()->detach($product->id);

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($package)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($package)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.package', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('ecommerce.packages.edit', ['package' => $package->id])
            ])
            ->sendJson();
    }
}
