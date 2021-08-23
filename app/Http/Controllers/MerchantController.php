<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Status;
use App\Models\Package;
use App\Models\Product;
use App\Models\Service;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\MerchantDataTable;
use App\Http\Requests\MerchantRequest;
use App\Support\Facades\MerchantFacade;
use App\DataTables\SubscriptionLogsDataTable;

class MerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:merchant.read'])->only(['index', 'show']);
        $this->middleware(['can:merchant.create'])->only(['create', 'store']);
        $this->middleware(['can:merchant.update'])->only(['edit', 'update']);
        $this->middleware(['can:merchant.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MerchantDataTable $dataTable)
    {
        return $dataTable->render('merchant.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Status::instance()->activeStatus();
        $services = Service::orderBy('name', 'asc')->get();

        return view('merchant.create', compact('statuses', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $merchant = MerchantFacade::setRequest($request)->storeData()->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('merchants.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $merchant)
    {
        $user_details = $merchant->userDetail()
            ->approvedDetails()
            ->with(['media'])
            ->first();

        $documents = $merchant->media()
            ->ssm()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('merchant.show', compact('merchant', 'documents', 'user_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $merchant, SubscriptionLogsDataTable $dataTable)
    {
        $merchant->load([
            'media',
            'userDetail' => function ($query) {
                $query->approvedDetails();
            },
            'userSubscriptions' => function ($query) {
                $query->with(['userSubscriptionLogs'])->active();
            }
        ]);

        $user_details   =   $merchant->userDetail;
        $logo           =   $merchant->media()->logo()->first();
        $documents      =   $merchant->media()->ssm()->get();
        $subscription   =   $merchant->userSubscriptions->first();
        $statuses       =   Status::instance()->activeStatus();
        $services       =   Service::orderBy('name', 'asc')->get();

        $plans = ProductAttribute::whereHas('product', function ($query) {
            $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
        })->whereDoesntHave('userSubscriptions', function ($query) use ($merchant) {
            $query->where('user_id', $merchant->id)->active();
        })->get();

        $packages = Package::with(['userSubscriptions'])
            ->whereDoesntHave('userSubscriptions', function ($query) use ($merchant) {
                $query->where('user_id', $merchant->id)->active(); // exlucde current merchant's active plan
            })
            ->get();

        foreach ($packages as $package) {
            $plans->push($package);
        }

        return $dataTable->with(['merchant_id', $merchant->id])
            ->render('merchant.edit', compact('merchant', 'documents', 'user_details', 'statuses', 'logo', 'subscription', 'plans', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantRequest $request, User $merchant)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $merchant->load([
                'media', 'address',
                'userDetail' => function ($query) {
                    $query->approvedDetails();
                },
                'userSubscriptions' => function ($query) {
                    $query->active();
                }
            ]);

            $merchant = MerchantFacade::setModel($merchant)
                ->setRequest($request)
                ->storeData()
                ->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('merchants.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $merchant)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module);

        try {

            $merchant->delete();

            $message = Message::instance()->format($action, $module, 'success');
            $status = 'success';

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchants.index')
            ])
            ->sendJson();
    }
}
