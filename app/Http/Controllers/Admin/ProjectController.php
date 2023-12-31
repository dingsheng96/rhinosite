<?php

namespace App\Http\Controllers\Admin;

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
use App\DataTables\Admin\ProjectDataTable;
use App\Http\Requests\Admin\ProjectRequest;
use App\DataTables\Admin\AdsBoostingDataTable;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:project.read']);
        $this->middleware(['can:project.create'])->only(['create', 'store']);
        $this->middleware(['can:project.update'])->only(['edit', 'update', 'deleteMedia']);
        $this->middleware(['can:project.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectDataTable $dataTable)
    {
        return $dataTable->render('admin.project.index');
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
        $ads_boosters       =   [];

        return view('admin.project.create', compact('max_files', 'statuses', 'default_currency', 'ads_boosters'));
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

            activity()->useLog('admin:project')
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
                    'redirect_to' => route('admin.projects.index')
                ])
                ->sendJson()
                : redirect()->route('admin.projects.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:project')
                ->causedBy(Auth::user())
                ->performedOn(new Project())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return $request->ajax()
                ? Response::instance()
                ->withStatusCode('modules.project', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->sendJson()
                : redirect()->back()->with($status, $message);
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
            'media', 'address',
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
            ->whereNotNull('slot_type')->whereNotNull('total_slots')
            ->whereHas('userAdsQuotas', function ($query) use ($project) {
                $query->where('user_id', $project->user_id)->where('quantity', '>', 0);
            })->orderBy('name')->get();

        return $dataTable->with(compact('project'))->render('admin.project.edit', compact('project', 'max_files', 'media', 'default_price', 'statuses', 'ads_boosters'));
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
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $project = ProjectFacade::setModel($project)->setRequest($request)->storeData()->getModel();

            DB::commit();

            $message =  Message::instance()->format($action, $module, 'success');
            $status  =  'success';

            activity()->useLog('admin:project')
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
                    'redirect_to' => route('admin.projects.index')
                ])
                ->sendJson()
                : redirect()->route('admin.projects.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:project')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return ($request->ajax())
                ? Response::instance()
                ->withStatusCode('modules.project', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->sendJson()
                : redirect()->back()->with($status, $message);
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

            $project->loadCount([
                'adsBoosters' => function ($query) {
                    $query->whereDate('boosted_at', '>=', today());
                }
            ]);

            throw_if($project->ads_boosters_count > 0, new \Exception('This project has upcoming boosting schedule.'));

            $project->delete();

            $message = Message::instance()->format($action, $module, 'success');
            $status = 'success';

            activity()->useLog('admin:project')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:project')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->log($e->getMessage());

            $message = $e->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.projects.index')
            ])
            ->sendJson();
    }
}
