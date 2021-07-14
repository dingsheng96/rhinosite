<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use App\Support\Facades\CartFacade;
use App\Support\Facades\OrderFacade;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\PackageFacade;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->is_merchant) {

            $subscription = Auth::user()
                ->userSubscriptions()
                ->active()
                ->with([
                    'userSubscriptionLogs' => function ($query) {
                        $query->active();
                    }
                ])
                ->first();

            $plans = Package::with(['products'])->orderBy('validity', 'asc')->get();

            return view('subscription.index', compact('subscription', 'plans'));
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
        //
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

    public function purchase($subscription)
    {
        try {

            $package = Package::with([
                'prices' => function ($query) {
                    $query->defaultPrice();
                }
            ])
                ->where('id', $subscription)
                ->orWhereHas('userSubscriptions', function ($query) {
                    $query->where('user_id', Auth::id())->inactive();
                })
                ->firstOrFail();

            CartFacade::setBuyer(Auth::user())->addToCart($package)->getModel();

            DB::commit();

            return redirect()->route('checkout.index');
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            return redirect()->back()->with('fail', $e->getMessage());
        }
    }
}
