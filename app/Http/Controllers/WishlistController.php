<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::published()
            ->with([
                'translations',
                'user' => function ($query) {
                    $query->with([
                        'userDetail' => function ($query) {
                            $query->approvedDetails();
                        }
                    ]);
                }
            ])
            ->whereHas('user', function ($query) {
                $query->merchant()->active();
            })
            ->when(Auth::user()->is_merchant, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->when(Auth::user()->is_member, function ($query) {
                $query->whereHas('wishlistedBy', function ($query) {
                    $query->where('id', Auth::id());
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'page', request()->get('page'));

        return view('wishlist.' . Auth::user()->folder_name, compact('projects'));
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
        $request->validate([
            'project' => ['required', 'exists:' . Project::class . ',id'],
        ]);

        DB::beginTransaction();
        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(__('modules.wishlist'));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);
        $liked      =   false;

        try {

            $project = Project::where('id', $request->get('project'))->published()
                ->whereHas('user', function ($query) {
                    $query->merchant()->active()
                        ->whereHas('userDetail', function ($query) {
                            $query->approvedDetails();
                        });
                    // ->whereHas('userSubscriptions', function ($query) {
                    //     $query->active();
                    // });
                })->select('id', 'user_id')->firstOrFail();

            $favouite_projects = Auth::user()->favouriteProjects();

            if ($favouite_projects->get()->contains($project->id)) {
                $favouite_projects->detach($project->id);
            } else {
                $favouite_projects->attach($project->id);
                $liked = true;
            }

            DB::commit();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData([
                'liked' => $liked
            ])
            ->sendJson();
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
