<?php

namespace App\Http\Controllers;

use App\Helpers\Misc;
use App\Helpers\Status;
use App\Models\Package;
use App\Models\Product;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\DataTables\PackageDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PackageRequest;
use App\Support\Facades\PackageFacade;

class PackageController extends Controller
{
    public $products, $stock_types, $statuses;

    public function __construct()
    {
        $this->products     =   Product::with(['productAttributes.prices'])->orderBy('name', 'asc')->get();
        $this->stock_types  =   Misc::instance()->packageStockTypes();
        $this->statuses     =   Status::instance()->activeStatus();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PackageDataTable $dataTable)
    {
        return $dataTable->render('package.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('package.create', [
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
        $module     =   strtolower(trans_choice('modules.package', 1));
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

            return redirect()->route('packages.index')->withSuccess($message);
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
    public function show(Package $package)
    {
        $package->load([
            'products',
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ]);

        $price = $package->prices->first();
        $items = $package->products;

        return view('package.show', [
            'package' => $package,
            'price' => $price,
            'items' => $items,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        $package->load([
            'products',
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ]);

        $price = $package->prices->first();
        $items = $package->products;

        return view('package.edit', [
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
        $module     =   strtolower(trans_choice('modules.package', 1));
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

            return redirect()->route('packages.index')->withSuccess($message);
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
        $module     =   strtolower(trans_choice('modules.package', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        DB::beginTransaction();

        try {

            throw_if(
                $package->userSubscriptions()->active()->count() > 0,
                new \Exception(__('messages.in_using', ['item' => $module]))
            );

            $package->prices()->delete();
            $package->carts()->delete();
            $package->delete();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($package)
                ->log($message);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            $message = $e->getMessage();

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
                'redirect_to' => route('packages.index')
            ])
            ->sendJson();
    }
}
