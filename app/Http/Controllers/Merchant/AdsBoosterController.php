<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Project;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\AdsBooster;
use App\Models\Permission;
use App\Models\UserAdsQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\ProjectFacade;
use App\Support\Facades\MerchantFacade;
use App\DataTables\Merchant\AdsDataTable;
use App\DataTables\Merchant\AdsBoostingDataTable;
use App\Http\Requests\Merchant\AdsBoosterRequest;

class AdsBoosterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdsDataTable $dataTable)
    {
        return $dataTable->render('merchant.ads.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::published()->where('user_id', Auth::id())
            ->orderBy('title', 'asc')->get();

        $ads_types = UserAdsQuota::with(['product'])
            ->whereHas('product', function ($query) {
                $query->active();
            })->where('quantity', '>', 0)
            ->where('user_id', Auth::id())->orderBy('product_id')->get();

        return view('merchant.ads.create', compact('projects', 'ads_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdsBoosterRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.ads', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $project = Project::where('id', $request->get('project'))->where('user_id', Auth::id())->published()->firstOrFail();

            $project = ProjectFacade::setModel($project)->setRequest($request)->storeBoostAds()->getModel();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('merchant:ads_booster')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('merchant.ads-boosters.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('merchant:ads_booster')
                ->causedBy(Auth::user())
                ->performedOn(new AdsBooster())
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
    public function show($ads_booster)
    {
        $booster = json_decode(base64_decode($ads_booster));

        $project = Project::with([
            'user' => function ($query) {
                $query->with(['projects', 'userAdsQuotas']);
            },
            'adsBoosters' => function ($query) use ($booster) {
                $query->with('product')->where('boost_index', $booster->boost_index)->orderBy('boosted_at');
            }
        ])
            ->where('id', $booster->boostable_id)
            ->whereHas('adsBoosters', function ($query) use ($booster) {
                $query->where('boost_index', $booster->boost_index)->orderBy('boosted_at');
            })->first();

        $projects   =   $project->user->projects;
        $ads_types  =   $project->userAdsQuotas;

        return view('merchant.ads.show', compact('project', 'projects', 'ads_types'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $ads_booster)
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
    public function destroy($ads_booster)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.ads', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $booster = json_decode(base64_decode($ads_booster));

        $project = Project::with([
            'user',
            'adsBoosters' => function ($query) use ($booster) {
                $query->with('product')->where('boost_index', $booster->boost_index)->orderBy('boosted_at');
            }
        ])->where('id', $booster->boostable_id)->whereHas('adsBoosters', function ($query) use ($booster) {
            $query->where('boost_index', $booster->boost_index)->orderBy('boosted_at');
        })->first();

        $products = [];
        foreach ($project->adsBoosters as $item) {
            $products[] = $item->product;
            $item->delete();
        }

        $product = collect($products)->unique()->first();

        MerchantFacade::setModel($project->user)->refundAdsQuota($product);

        activity()->useLog('merchant:ads_booster')
            ->causedBy(Auth::user())
            ->performedOn(new AdsBooster())
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.ads-boosters.index')
            ])
            ->sendJson();
    }
}
