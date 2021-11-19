<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
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
        $request->validate([
            'merchant' => ['required', 'exists:' . User::class . ',id'],
            'rating' => ['required', 'in:1,2,3,4,5'],
            'review' => ['nullable', 'max:255']
        ]);

        $action     =   Permission::ACTION_CREATE;
        $status     =   'fail';
        $message    =   'Unable to rate contractor.';
        $data       =   [];

        DB::beginTransaction();

        try {

            Auth::user()->ratedBy()->attach([
                $request->get('merchant') => ['scale' => $request->get('rating'), 'review' => $request->get('review'), 'created_at' => now()]
            ]);

            DB::commit();

            $rating = User::with('ratings')->where('id', $request->get('merchant'))->first();

            $status = 'success';
            $message = 'Contractor rated successfully.';
            $data = [
                'rated' => true,
                'rating' => $rating->rating_stars
            ];
        } catch (\Error | \Exception $ex) {

            DB::rollBack();
            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.' . $action . $status)
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
