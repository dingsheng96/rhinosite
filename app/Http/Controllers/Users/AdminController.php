<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Helpers\Response;
use Illuminate\Http\Request;
use App\DataTables\AdminDataTable;
use App\Models\Settings\Role\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Settings\Role\Permission;
use App\Http\Requests\Users\AdminRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdminDataTable $dataTable)
    {
        $statuses = Status::instance()->activationStatus();

        return $dataTable->render('users.admin.index', compact('statuses'));
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
    public function store(AdminRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.submodules.admin', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $input = $request->get('create');

            $admin = User::create([
                'name'      =>  $input['name'],
                'email'     =>  $input['email'],
                'password'  =>  Hash::make($input['password']),
                'status'    =>  $input['status']
            ]);

            $admin->assignRole(Role::ROLE_SUPER_ADMIN);

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($admin)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('users.admins.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()
                ->with('fail', $message)
                ->withInput();
        }
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
    public function destroy(User $admin)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.submodules.admin', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $admin->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($admin)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.admin', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('users.admins.index')
            ])
            ->sendJson();
    }
}
