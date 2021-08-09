<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Project;
use App\Helpers\Message;
use App\Models\AdsBooster;
use App\Models\Permission;
use App\Models\UserAdsQuota;
use Illuminate\Http\Request;
use App\DataTables\AdsDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\Facades\ProjectFacade;
use App\DataTables\AdsBoostingDataTable;
use App\Http\Requests\AdsBoosterRequest;

class AdsBoosterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:ads.read']);
        $this->middleware(['can:ads.create'])->only(['create', 'store']);
        $this->middleware(['can:ads.update'])->only(['edit', 'update']);
        $this->middleware(['can:ads.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdsDataTable $dataTable)
    {
        return $dataTable->render('ads.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user       =   Auth::user();
        $projects   =   [];
        $ads_types  =   [];
        $merchants  =   [];

        if ($user->is_admin) {

            $merchants  =   User::merchant()->active()->hasAdsQuota()->orderBy('name', 'asc')->get();
        } elseif ($user->is_merchant) {
            $projects = Project::published()
                ->where('user_id', Auth::id())
                ->orderBy('title', 'asc')
                ->get();

            $ads_types = UserAdsQuota::with(['product'])
                ->whereHas('product', function ($query) {
                    $query->active();
                })
                ->where('quantity', '>', 0)
                ->where('user_id', Auth::id())
                ->orderBy('product_id')
                ->get();
        }

        return view('ads.create', compact('projects', 'ads_types', 'merchants'));
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

            $project  = Project::where('id', $request->get('project'))
                ->where('user_id', $request->get('merchant', Auth::id()))
                ->published()->firstOrFail();

            $project = ProjectFacade::setModel($project)->setRequest($request)->storeBoostAds()->getModel();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('ads-boosters.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new AdsBooster())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()
                ->with('fail', $message)
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $ads_booster, AdsBoostingDataTable $dataTable)
    {
        $ads_booster->load([
            'adsBoosters' => function ($query) {
                $query->with(['product', 'boostable']);
            }
        ]);

        return $dataTable->with(['ads_booster' => $ads_booster])
            ->render('ads.show', compact('ads_booster'));
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
    public function destroy($id)
    {
        //
    }
}
