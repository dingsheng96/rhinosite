<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Support\Facades\CartFacade;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MerchantFacade;
use App\Http\Requests\SubscriptionRequest;
use App\Support\Facades\TransactionFacade;
use App\Support\Facades\UserSubscriptionFacade;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        abort_if(!$user->is_merchant && !$user->is_admin, 404);

        if ($user->is_merchant) {

            $user->load([
                'userSubscriptions' => function ($query) {
                    $query->active();
                }
            ]);

            $subscription = $user->userSubscriptions->first();

            $plans = ProductAttribute::with([
                'prices' => function ($query) {
                    $query->defaultPrice();
                }
            ])->published()->trialMode(false)
                ->whereHas('product', function ($query) {
                    $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
                })->get();

            return view('subscription.index', compact('subscription', 'plans', 'user'));
        }

        return $this->create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $merchants = User::with(['userDetail', 'userSubscriptions'])
            ->merchant()
            ->whereHas('userDetail', function ($query) {
                $query->approvedDetails();
            })
            ->whereDoesntHave('userSubscriptions', function ($query) {
                $query->active();
            })
            ->orderBy('name')->get();

        $plans = ProductAttribute::with([
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ])->whereHas('product', function ($query) {
            $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
        })->get();

        $packages = Package::with(['userSubscriptions'])->get();

        foreach ($packages as $package) {
            $plans->push($package);
        }

        $payment_methods = PaymentMethod::where('system_default', false)->orderBy('name')->get();

        return view('subscription.create', compact('plans', 'merchants', 'payment_methods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionRequest $request)
    {
        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';
        $user       =   User::with('carts')->merchant()->where('id', $request->get('merchant', Auth::id()))->firstOrFail();

        DB::beginTransaction();

        try {

            $item   =   json_decode($request->get('plan'));
            $trial  =   false;
            $transaction = null;

            // get item model
            switch ($item->class) {
                case ProductAttribute::class:
                    $item_model =   ProductAttribute::findOrFail($item->id);
                    $trial      =   $item_model->trial_mode;
                    break;
                case Package::class:
                    $item_model =   Package::findOrFaiL($item->id);
                    break;
            }

            CartFacade::setBuyer($user)->addToCart($item_model)->getModel();

            if (empty($request->get('merchant')) && Auth::user()->is_merchant) {
                DB::commit();
                return redirect()->route('checkout.index');
            }

            if (!$trial && Auth::user()->is_admin) {
                // store order
                $order = OrderFacade::setRequest($request)
                    ->setBuyer($user)
                    ->createOrder()
                    ->setOrderStatus(Order::STATUS_PAID)
                    ->getModel();

                // create new transaction from order
                $transaction = TransactionFacade::setParent($order)
                    ->setRequest($request)
                    ->newTransaction()
                    ->setTransactionStatus(Transaction::STATUS_SUCCESS)
                    ->getModel();
            }

            $merchant  = MerchantFacade::setModel($user)
                ->storeSubscription($request->get('plan'), $transaction)
                ->getModel();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->withProperties($request->all())
                ->log($message);

            DB::commit();

            return redirect()->route('subscriptions.create')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            if (!$request->has('merchant') || empty($request->get('merchant'))) {
                return redirect()->back()->with('fail', $e->getMessage());
            }

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($user)
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserSubscription $subscription)
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

    /**
     * Terminate the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function terminate(Request $request, UserSubscription $subscription)
    {
        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.subscription', 1));
        $message    =   'Subscription unable to terminate.';
        $status     =   'fail';

        DB::beginTransaction();

        try {

            $subscription->load(['user', 'transaction']);

            $subscription = UserSubscriptionFacade::setModel($subscription)->terminate()->getModel();

            if (!empty($subscription->terminated_at)) {
                $status  =  'success';
                $message =  'Subscription terminated successfully!';
            }

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($subscription)
                ->withProperties($request->all())
                ->log($message);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($subscription)
                ->withProperties($request->all())
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.user', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.account.index')
            ])
            ->sendJson();
    }
}
