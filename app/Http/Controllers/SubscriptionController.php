<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Helpers\Message;
use App\Models\Permission;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Support\Facades\CartFacade;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MerchantFacade;
use App\Http\Requests\SubscriptionRequest;
use App\Support\Facades\TransactionFacade;

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
        $merchants = User::merchant()->orderBy('name')->get();

        $plans = ProductAttribute::with([
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ])->published()->whereHas('product', function ($query) {
            $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION)->trialMode(false);
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
        $user       =   User::with('carts')->merchant()
            ->where('id', $request->get('merchant', Auth::id()))
            ->firstOrFail();

        try {

            $item = json_decode($request->get('plan'));

            // get item model
            switch ($item->class) {
                case ProductAttribute::class:
                    $item_model = ProductAttribute::findOrFail($item->id);
                    break;
                case Package::class:
                    $item_model = Package::findOrFaiL($item->id);
                    break;
            }

            CartFacade::setBuyer($user)->addToCart($item_model)->getModel();

            if (!$request->has('merchant') || empty($request->get('merchant'))) {
                DB::commit();
                return redirect()->route('checkout.index');
            }

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

            $merchant  = MerchantFacade::setModel($user)
                ->setRequest($request)
                ->storeSubscription()
                ->getModel();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

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
    public function update(Request $request, Package $subscription)
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
}
