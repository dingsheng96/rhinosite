<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\UserAdsQuota;
use Illuminate\Http\Request;
use App\DataTables\AdsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdsController extends Controller
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
        $merchants = null;

        if (Auth::user()->is_admin) {
            $merchants = User::merchant()->active()->hasAdsQuota()->orderBy('name', 'asc')->get();
        }

        $projects = Project::published()
            ->when(Auth::user()->is_merchant, function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('title', 'asc')
            ->get();

        $ads_types = UserAdsQuota::with(['productAttribute'])
            ->whereHas('productAttribute', function ($query) {
                $query->active();
            })
            ->when(Auth::user()->is_merchant, function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('ads.create', compact('projects', 'ads_types'));
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
