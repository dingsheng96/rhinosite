<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Price;
use App\Models\Project;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Helpers\FileManager;
use Illuminate\Support\Facades\DB;
use App\DataTables\ProjectDataTable;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectRequest;
use App\Support\Facades\ProjectFacade;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectDataTable $dataTable)
    {
        return $dataTable->render('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $max_files = Project::MAX_IMAGES;

        return view('projects.create', compact('max_files'));
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
    public function show(Project $project)
    {
        $media      =   $project->media();
        $images     =   (clone ($media))->image()->get();
        $thumbnail  =   (clone ($media))->thumbnail()->first();

        return view('projects.show', compact('project', 'images', 'thumbnail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $media      =   $project->media()->image()->get();
        $max_files  =   Project::MAX_IMAGES - $media->count();
        $prices     =   $project->prices()->orderBy('created_at', 'desc')->get();

        return view('projects.edit', compact('project', 'max_files', 'media', 'prices'));
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

            activity()->useLog('web')
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
                    'redirect_to' => route('projects.index')
                ])
                ->sendJson()
                : redirect()->route('projects.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
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

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($project)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('projects.index')
            ])
            ->sendJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMedia(Project $project, Media $media)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.image', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module);

        try {

            $file = $project->media()->where('id', $media->id)->first();

            if ($file) {
                FileManager::instance()->removeFile($file->file_path);
                $file->delete();
            }

            $message = Message::instance()->format($action, $module, 'success');
            $status = 'success';

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($media)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($media)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('projects.edit', ['project' => $project->id])
            ])
            ->sendJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePrice(Project $project, Price $price)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.price', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $price->delete();
            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($price)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($price)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('projects.edit', ['project' => $project->id])
            ])
            ->sendJson();
    }
}
