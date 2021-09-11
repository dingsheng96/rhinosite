<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->load([
            'favouriteProjects'
        ]);

        $compare_lists = Auth::user()->comparisons()
            ->with([
                'media', 'translations', 'address',
                'prices' => function ($query) {
                    $query->defaultPrice();
                },
                'user.userDetail' => function ($query) {
                    $query->with(['service'])->approvedDetails();
                }
            ])->get();

        return view('member.compare', compact('compare_lists', 'user'));
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
            'target' => 'required|in:project',
            'target_id' => 'required|exists:' . Project::class . ',id',
        ]);

        $action     =   Permission::ACTION_UPDATE;
        $status     =   'fail';
        $message    =   'Unable to add project to compare list.';
        $data       =   [];

        DB::beginTransaction();

        try {

            $compare_lists = Auth::user()->comparisons();

            if ($request->get('target') == 'project') {

                $project = Project::findOrFail($request->get('target_id'));

                if ($compare_lists->get()->contains($project->id)) {

                    $compare_lists->detach($project->id);

                    $message = 'Project removed from compare list.';
                } else {

                    throw_if($compare_lists->count() >= 3, new \Exception(__('messages.compare_list_reached_limit')));

                    $compare_lists->attach($project->id);

                    $message = 'Project added to compare list.';
                }
            }

            $status = 'success';
            $data = [
                'selected' => $compare_lists->count(),
                'attached' => $compare_lists->pluck('id')
            ];

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();
            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data)
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
