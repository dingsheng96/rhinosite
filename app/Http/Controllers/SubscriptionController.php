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
    public function update(Request $request, $id)
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

    public function purchase($package)
    {
        try {

            $package = Package::where('id', $package)->firstOrFail();

            $request = new Request();

            $request->request->add([
                'item' => [
                    'item_id'   =>  $package->id,
                    'type'      =>  'package',
                    'quantity'  =>  1
                ]
            ]);

            CartFacade::setRequest($request)
                ->setBuyer(Auth::user())
                ->addToCart()
                ->getModel();

            DB::commit();

            return redirect()->route('carts.index');
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('fail', $e->getMessage());
        }
    }
}
