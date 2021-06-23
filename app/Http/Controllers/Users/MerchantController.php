<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Models\Media;
use App\Models\Category;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\MerchantDataTable;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MerchantDataTable $dataTable)
    {
        return $dataTable->render('users.merchant.index');
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
    public function edit(User $merchant)
    {
        $user_details = UserDetails::approvedDetails()
            ->with(['media'])
            ->where('user_id', $merchant->id)
            ->first();

        $documents = $user_details->media()
            ->ssmDocuments()
            ->orderBy('created_at', 'asc')
            ->get();

        $categories = Category::orderBy('name', 'asc')->get();

        return view('users.merchant.edit', compact('merchant', 'documents', 'user_details', 'categories'));
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
