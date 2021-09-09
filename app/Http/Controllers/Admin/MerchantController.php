<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Status;
use App\Models\Service;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\UserDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MerchantFacade;
use App\DataTables\Admin\MerchantDataTable;
use App\Http\Requests\Admin\MerchantRequest;
use App\DataTables\Admin\SubscriptionLogsDataTable;

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
        return $dataTable->render('admin.merchant.index');
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

        return view('admin.merchant.create', compact('statuses', 'services'));
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
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $merchant = MerchantFacade::setRequest($request)->storeData()
                ->setUserDetailStatus(UserDetail::STATUS_APPROVED)->verifiedEmail()->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:merchant')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->withProperties($request->except(['password', 'password_confirmation']))
                ->log($message);

            return redirect()->route('admin.merchants.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:merchant')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->except(['password', 'password_confirmation']))
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
    public function show(User $merchant, SubscriptionLogsDataTable $dataTable)
    {
        $merchant->load([
            'media' => function ($query) {
                $query->ssm()->orWhere(function ($query) {
                    $query->logo();
                });
            },
            'userDetail' => function ($query) {
                $query->approvedDetails();
            },
            'userSubscriptions' => function ($query) {
                $query->with([
                    'userSubscriptionLogs' => function ($query) {
                        $query->latest('created_at')->limit(1);
                    }
                ])->active()->limit(1);
            }
        ]);

        $subscription       = optional($merchant->userSubscriptions)->first();
        $subscription_log   = optional(optional($subscription)->userSubscriptionLogs)->first();

        return $dataTable->with(compact('merchant'))->render('admin.merchant.show', compact('merchant', 'subscription', 'subscription_log'));
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
            'media' => function ($query) {
                $query->ssm()->orWhere(function ($query) {
                    $query->logo();
                });
            },
            'userDetail' => function ($query) {
                $query->approvedDetails();
            },
            'userSubscriptions' => function ($query) {
                $query->with([
                    'userSubscriptionLogs' => function ($query) {
                        $query->latest('created_at')->limit(1);
                    }
                ])->active()->limit(1);
            }
        ]);

        $statuses           = Status::instance()->activeStatus();
        $services           = Service::orderBy('name', 'asc')->get();
        $subscription       = optional($merchant->userSubscriptions)->first();
        $subscription_log   = optional(optional($subscription)->userSubscriptionLogs)->first();

        return $dataTable->with(compact('merchant'))->render('admin.merchant.edit', compact('merchant', 'statuses', 'subscription', 'subscription_log', 'services'));
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
                'media', 'address', 'userDetail',
                'userSubscriptions' => function ($query) {
                    $query->active();
                }
            ]);

            $merchant = MerchantFacade::setModel($merchant)->setRequest($request)
                ->storeData()->setUserDetailStatus(UserDetail::STATUS_APPROVED)->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:merchant')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->withProperties($request->except(['password', 'password_confirmation']))
                ->log($message);

            return redirect()->route('admin.merchants.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:merchant')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->except(['password', 'password_confirmation']))
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
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $merchant->delete();

            $status = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:merchant')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:merchant')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.merchants.index')
            ])
            ->sendJson();
    }
}
