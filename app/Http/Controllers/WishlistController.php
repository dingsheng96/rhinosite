<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
            ->with(['translations', 'user.userDetail'])
            ->whereHas('user', function ($query) {
                $query->merchant()->active();
            })
            ->when(Auth::user()->is_merchant, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->when(Auth::user()->is_member, function ($query) {
                $query->whereHas('wishlists', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            })
            ->select('id', DB::raw('COUNT(*) AS projects_count'))
            ->groupBy('id')
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
    public function destroy($id)
    {
        //
    }
}
