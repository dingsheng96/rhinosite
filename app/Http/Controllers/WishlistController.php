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
        $user = Auth::user()->load(['favouriteProjects']);

        $projects = Project::published()
            ->with([
                'translations', 'user' => function ($query) {
                    $query->with([
                        'userDetail' => function ($query) {
                            $query->approvedDetails();
                        }
                    ]);
                }
            ])->whereHas('user', function ($query) {
                $query->validMerchant();
            })->whereHas('wishlistedBy', function ($query) {
                $query->where('id', Auth::id());
            })->orderByDesc('created_at')
            ->paginate(15, ['*'], 'page', request()->get('page'));

        return view('member.wishlist', compact('projects', 'user'));
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
        $action         =   Permission::ACTION_CREATE;
        $module         =   strtolower(__('modules.wishlist'));
        $status         =   'fail';
        $message        =   Message::instance()->format($action, $module, $status);
        $liked          =   false;

        try {

            $project = Project::where('id', $request->get('project'))->published()
                ->whereHas('user', function ($query) {
                    $query->validMerchant();
                })->select('id', 'user_id')->first();
            if (empty($project)) {
                $project = Project::where('id', $request->get('project'))->published()
                    ->whereHas('user', function ($query) {
                        $query->freeTierMerchant(true);
                    })->select('id', 'user_id')->firstOrFail();
            }

            Auth::user()->favouriteProjects()->toggle($project->id);

            $liked = Auth::user()->favouriteProjects()->get()->contains($project->id);

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
                'liked' => $liked,
                'refresh_page' => $request->has('refresh_page')
            ])
            ->sendJson();
    }
}
