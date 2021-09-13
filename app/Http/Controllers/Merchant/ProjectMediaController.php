<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Media;
use App\Models\Project;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Helpers\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProjectMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function destroy(Project $project, Media $medium)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.image', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module);

        try {

            FileManager::instance()->removeFile($medium->file_path);

            $medium->delete();

            $message = Message::instance()->format($action, $module, 'success');
            $status = 'success';

            activity()->useLog('merchant:project')
                ->causedBy(Auth::user())
                ->performedOn($medium)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('merchant:project')
                ->causedBy(Auth::user())
                ->performedOn($medium)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.projects.edit', ['project' => $project->id])
            ])
            ->sendJson();
    }
}
