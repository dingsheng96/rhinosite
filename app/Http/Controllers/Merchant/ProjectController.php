<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Media;
use App\Helpers\Status;
use App\Models\Product;
use App\Models\Project;
use App\Helpers\Message;
use App\Models\Currency;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\ProjectFacade;
use App\DataTables\Merchant\ProjectDataTable;
use App\Http\Requests\Merchant\ProjectRequest;
use App\DataTables\Merchant\AdsBoostingDataTable;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectDataTable $dataTable)
    {
        return $dataTable->render('merchant.project.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $max_files          =   Media::MAX_IMAGE_PROJECT;
        $statuses           =   Status::instance()->projectStatus();
        $default_currency   =   Currency::defaultCountryCurrency()->first();
        $ads_boosters       =   Product::with(['userAdsQuotas'])
            ->filterCategory(ProductCategory::TYPE_ADS)
            ->active()
            ->whereHas('userAdsQuotas', function ($query) {
                $query->where('user_id', Auth::id());
            })->orderBy('name')->get();

        return view('merchant.project.create', compact('max_files', 'statuses', 'default_currency', 'ads_boosters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.project', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $project = ProjectFacade::setRequest($request)->storeData()->getModel();

            DB::commit();

            $message =  Message::instance()->format($action, $module, 'success');
            $status  =  'success';

            activity()->useLog('merchant:project')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->withProperties($request->all())
                ->log($message);

            return $request->ajax()
                ? Response::instance()
                ->withStatusCode('modules.project', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->withData([
                    'redirect_to' => route('merchant.projects.index')
                ])
                ->sendJson()
                : redirect()->route('merchant.projects.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('merchant:project')
                ->causedBy(Auth::user())
                ->performedOn(new Project())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return Response::instance()
                ->withStatusCode('modules.project', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->sendJson();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return redirect()->route('app.project.show', ['project' => $project->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, AdsBoostingDataTable $dataTable)
    {
        $project->load([
            'media',
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ]);

        $thumbnail          =   $project->thumbnail;
        $media              =   $project->media()->image()->get();
        $max_files          =   Media::MAX_IMAGE_PROJECT - $media->count();
        $default_price      =   $project->prices->first();
        $statuses           =   Status::instance()->projectStatus();
        $ads_boosters       =   Product::with(['userAdsQuotas'])->active()
            ->whereNotNull('total_slots')->whereNotNull('slot_type')
            ->whereHas('userAdsQuotas', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('name')->get();

        return $dataTable->with(['project' => $project])->render('merchant.project.edit', compact('project', 'max_files', 'media', 'default_price', 'statuses', 'ads_boosters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.project', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $project = ProjectFacade::setModel($project)->setRequest($request)->storeData()->getModel();

            DB::commit();

            $message =  Message::instance()->format($action, $module, 'success');
            $status  =  'success';

            activity()->useLog('merchant:project')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->withProperties($request->all())
                ->log($message);

            return ($request->ajax())
                ? Response::instance()
                ->withStatusCode('modules.project', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->withData([
                    'redirect_to' => route('merchant.projects.index')
                ])
                ->sendJson()
                : redirect()->route('merchant.projects.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('merchant:project')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return Response::instance()
                ->withStatusCode('modules.project', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->sendJson();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.project', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module);

        try {

            $project->delete();

            $message = Message::instance()->format($action, $module, 'success');
            $status = 'success';

            activity()->useLog('merchant:project')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('merchant:project')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.projects.index')
            ])
            ->sendJson();
    }
}
