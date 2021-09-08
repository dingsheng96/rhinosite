<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Module;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Admin\Admin\RoleDataTable;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('admin.role.index');
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
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();

        $action = Permission::ACTION_CREATE;
        $module = strtolower(trans_choice('modules.role', 1));
        $message = Message::instance()->format($action, $module);

        try {

            $input = $request->get('create');

            $role = Role::create([
                'name'          =>  $input['name'],
                'description'   =>  $input['description'],
                'guard'         =>  config('auth.default.guard')
            ]);

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($role)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.roles.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new Role())
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
    public function show(Role $role)
    {
        $role->load(['permissions']);

        $actions = Permission::select('action')
            ->groupBy('action')
            ->orderBy('action', 'asc')
            ->get();

        $modules = Module::orderBy('name', 'asc')
            ->with([
                'permissions' => function ($query) {
                    $query->orderBy('action', 'asc');
                }
            ])
            ->get();

        return view('admin.role.show', compact('role', 'actions', 'modules'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $role->load(['permissions']);

        $actions = Permission::select('action')
            ->groupBy('action')
            ->orderBy('action', 'asc')
            ->get();

        $modules = Module::orderBy('name', 'asc')
            ->with([
                'permissions' => function ($query) {
                    $query->orderBy('action', 'asc');
                }
            ])
            ->get();

        return view('admin.role.edit', compact('role', 'actions', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        DB::beginTransaction();

        $action = Permission::ACTION_UPDATE;
        $module = strtolower(trans_choice('modules.role', 1));
        $message = Message::instance()->format($action, $module);

        try {
            $role->name         =   $request->get('name');
            $role->description  =   $request->get('description');
            $role->syncPermissions($request->get('permissions'));

            if ($role->isDirty()) {
                $role->save();
            }

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($role)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.roles.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($role)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()
                ->with('fail', $message)
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.role', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $role->syncPermissions([]);
        $role->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($role)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.role', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.roles.index')
            ])
            ->sendJson();
    }
}
