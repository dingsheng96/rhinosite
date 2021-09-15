<?php

namespace App\Http\Controllers\Merchant;

use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\Transaction;
use Illuminate\Http\Request;
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
use App\Http\Requests\Merchant\SubscriptionRequest;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->load([
            'userSubscriptions' => function ($query) {
                $query->active();
            }
        ]);

        $subscription = $user->userSubscriptions->first();

        $plans = ProductAttribute::with([
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ])->published()->trialMode(false)->whereHas('product', function ($query) {
            $query->whereNull('total_slots')->whereNull('slot_type');
        })->get();

        return view('merchant.subscription.index', compact('subscription', 'plans', 'user'));
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
    public function store(SubscriptionRequest $request)
    {
        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';
        $user       =   User::with('carts')->merchant()->where('id', $request->get('merchant', Auth::id()))->firstOrFail();

        DB::beginTransaction();

        try {

            $item   =   json_decode(base64_decode($request->get('plan')));
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

            DB::commit();

            return redirect()->route('merchant.checkout.index');
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            if (!$request->has('merchant') || empty($request->get('merchant'))) {
                return redirect()->back()->with('fail', $e->getMessage());
            }

            activity()->useLog('merchant:subscription')
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

            activity()->useLog('merchant:subscription')
                ->causedBy(Auth::user())
                ->performedOn($subscription)
                ->withProperties($request->all())
                ->log($message);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('merchant:subscription')
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
                'redirect_to' => route('merchant.account.index')
            ])
            ->sendJson();
    }
}
