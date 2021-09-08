<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\Services\AdminService;
use App\DataTables\Admin\AdminDataTable;
use App\Http\Requests\Admin\AdminRequest;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:admin.read']);
        $this->middleware(['can:admin.create'])->only(['create', 'store']);
        $this->middleware(['can:admin.update'])->only(['edit', 'update']);
        $this->middleware(['can:admin.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdminDataTable $dataTable)
    {


        return $dataTable->render('admin.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles    = Role::orderBy('name')->get();
        $statuses = Status::instance()->activeStatus();

        return view('admin.admin.create', compact('statuses', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request, AdminService $admin_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.admin', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $admin = $admin_service->setRequest($request)->store()->getModel();

            $status =   'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:admin')
                ->causedBy(Auth::user())
                ->performedOn($admin)
                ->withProperties($request->except(['password', 'password_confirmation']))
                ->log($message);

            DB::commit();

            return redirect()->route('admin.admins.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:admin')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->except(['password', 'password_confirmation']))
                ->log($e->getMessage());

            return redirect()->back()->with('fail', $message)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $admin)
    {
        $admin->load('roles');

        return view('admin.admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $admin)
    {
        $roles    = Role::orderBy('name')->get();
        $statuses = Status::instance()->activeStatus();

        $admin->load('roles');

        return view('admin.admin.edit', compact('statuses', 'roles', 'admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, User $admin, AdminService $admin_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.admin', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $admin_service->setModel($admin)->setRequest($request)->store();

            $status = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:admin')
                ->causedBy(Auth::user())
                ->performedOn($admin)
                ->withProperties($request->except(['password', 'password_confirmation']))
                ->log($message);

            DB::commit();

            return redirect()->route('admin.admins.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:admin')
                ->causedBy(Auth::user())
                ->performedOn($admin)
                ->withProperties($request->except(['password', 'password_confirmation']))
                ->log($e->getMessage());

            return redirect()->back()->with('fail', $message)->withInput();
        }
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
        $module     =   strtolower(trans_choice('modules.admin', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $admin->delete();

        activity()->useLog('admin:admin')
            ->causedBy(Auth::user())
            ->performedOn($admin)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.admin', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.admins.index')
            ])
            ->sendJson();
    }
}
