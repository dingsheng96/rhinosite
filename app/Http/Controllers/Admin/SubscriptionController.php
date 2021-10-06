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
use App\Support\Facades\TransactionFacade;
use App\Support\Facades\UserSubscriptionFacade;
use App\Http\Requests\Admin\SubscriptionRequest;

class SubscriptionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $merchants = User::with(['userDetail', 'userSubscriptions'])
            ->merchant()->withApprovedDetails()->active()
            ->where(function ($query) {
                $query->freeTierMerchant(false);
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

        return view('admin.subscription.create', compact('plans', 'merchants', 'payment_methods'));
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

            $item           =   json_decode(base64_decode($request->get('plan')));
            $trial          =   false;
            $transaction    =   null;

            // get item model
            switch ($item->class) {
                case ProductAttribute::class:
                    $item_model =   ProductAttribute::findOrFail($item->id);
                    $trial      =   $item->trial ?? false;
                    break;
                case Package::class:
                    $item_model =   Package::findOrFaiL($item->id);
                    $trial      =   $item->trial ?? false;
                    break;
            }

            if (!$trial) {

                CartFacade::setBuyer($user)->addToCart($item_model);

                // store order
                $order = OrderFacade::setRequest($request)
                    ->setBuyer($user)->createOrder()
                    ->setOrderStatus(Order::STATUS_PAID)
                    ->getModel();

                // create new transaction from order
                $transaction = TransactionFacade::setParent($order)
                    ->setRequest($request)->newTransaction()
                    ->setTransactionStatus(Transaction::STATUS_SUCCESS)
                    ->getModel();
            }

            $merchant  = MerchantFacade::setModel($user)
                ->setRequest($request)
                ->storeSubscription($request->get('plan'), $transaction)
                ->getModel();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:subscription')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->withProperties($request->all())
                ->log($message);

            DB::commit();

            return redirect()->route('admin.subscriptions.create')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:subscription')
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->withErrors($message)->withInput();
        }
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

            activity()->useLog('admin:subscription')
                ->causedBy(Auth::user())
                ->performedOn($subscription)
                ->withProperties($request->all())
                ->log($message);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:subscription')
                ->causedBy(Auth::user())
                ->performedOn($subscription)
                ->withProperties($request->all())
                ->log($e->getMessage());
        }

        return ($request->ajax())
            ? Response::instance()
            ->withStatusCode('modules.user', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.merchants.index')
            ])
            ->sendJson()
            : redirect()->route('admin.merchants.index')->with($status, $message);
    }
}
