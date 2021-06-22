<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Helpers\Message;
use App\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\ProjectDataTable;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectRequest;
use App\Support\Facades\ProjectFacade;
use App\Models\Settings\Country\Country;
use App\Models\Settings\Role\Permission;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ProjectDataTable $dataTable)
    {
        if (Auth::user()->is_merchant) {

            $projects = Project::where('user_id', Auth::user()->id)
                ->with(['translations'])
                ->paginate(15, ['*'], 'page', $request->get('page'));

            return view('projects.index.' . Auth::user()->folder_name, compact('projects'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
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

            $project = ProjectFacade::setRequest($request)
                ->storeData()
                ->getModel();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');
            $status  = 'success';

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->withProperties($request->all())
                ->log($message);

            return Response::instance()
                ->withStatusCode('modules.project', 'actions.' . $action . $status)
                ->withStatus($status)
                ->withMessage($message, true)
                ->withData([
                    'redirect_to' => route('projects.index')
                ])
                ->sendJson();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
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
}
