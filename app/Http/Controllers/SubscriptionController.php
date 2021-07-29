<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Support\Facades\CartFacade;
use App\Http\Controllers\Controller;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\PackageFacade;
use App\Http\Requests\SubscriptionRequest;
use App\Rules\CheckSubscriptionPlanExists;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!Auth::user()->is_merchant, 404);

        $user = Auth::user()->load([
            'userSubscriptions' => function ($query) {
                $query->active();
            }
        ]);

        if ($user->is_merchant) {

            $subscription = $user->userSubscriptions->first();

            $plans = ProductAttribute::with([
                'prices' => function ($query) {
                    $query->defaultPrice();
                }
            ])->whereHas('product', function ($query) {
                $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
            })->get();

            $packages = Package::with(['userSubscriptions', 'products'])->get();

            foreach ($packages as $package) {
                $plans->push($package);
            }

            return view('subscription.index', compact('subscription', 'plans', 'user'));
        }
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
        $user = Auth::user();

        abort_if(!$user->is_merchant, 404);

        $request->validate([
            'plan' => ['required', new CheckSubscriptionPlanExists(Auth::user())]
        ]);

        try {

            $item = json_decode($request->get('plan'));

            if ($item->class == ProductAttribute::class) {

                $item_model = ProductAttribute::with(['userSubscriptions'])
                    ->where('id', $item->id)
                    ->whereHas('product', function ($query) {
                        $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
                    })->whereDoesntHave('userSubscriptions', function ($query) use ($user) {
                        $query->where('user_id', $user->id)->active();
                    })->firstOrFail();
            }

            if ($item->class == Package::class) {
                $item_model = Package::with(['userSubscriptions'])
                    ->where('id', $item->id)
                    ->whereDoesntHave('userSubscriptions', function ($query) use ($user) {
                        $query->where('user_id', $user->id)->active(); // exlucde current merchant's active plan
                    })->firstOrFail();
            }

            CartFacade::setBuyer(Auth::user())->addToCart($item_model)->getModel();

            DB::commit();

            return redirect()->route('checkout.index');
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('fail', $e->getMessage());
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
