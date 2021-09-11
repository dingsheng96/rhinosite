<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AccountRequest;
use App\Support\Facades\MemberFacade;
use App\Support\Services\AdminService;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->load('address');

        return view('member.account', compact('user'));
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
    public function store(AccountRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(__('labels.user_account'));
        $status     =   'fail';

        try {

            $account = MemberFacade::setModel(Auth::user())->setRequest($request)->storeData()->getModel();

            DB::commit();

            $status  =  'success';

            if (!empty($request->get('password'))) {
                Auth::guard('web')->login($account);
            }
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message =  Message::instance()->format($action, $module, $status);

        activity()->useLog('admin:account')
            ->causedBy(Auth::user())
            ->performedOn(Auth::user())
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        return redirect()->route('app.account.index')->with($status, $message);
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
