<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MemberFacade;
use App\DataTables\Admin\MemberDataTable;
use App\Http\Requests\Admin\MemberRequest;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:member.read']);
        $this->middleware(['can:member.create'])->only(['create', 'store']);
        $this->middleware(['can:member.update'])->only(['edit', 'update']);
        $this->middleware(['can:member.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MemberDataTable $dataTable)
    {
        return $dataTable->render('admin.member.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Status::instance()->activeStatus();

        return view('admin.member.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.member', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $member = MemberFacade::setRequest($request)->storeData()->verifiedEmail()->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:member')
                ->causedBy(Auth::user())
                ->performedOn($member)
                ->withProperties($request->except(['password', 'password_confirmation']))
                ->log($message);

            return redirect()->route('admin.members.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:member')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->except(['password', 'password_confirmation']))
                ->log($e->getMessage());

            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $member)
    {
        $member->load(['address']);

        return view('admin.member.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $member)
    {
        $member->load(['address']);

        $statuses = Status::instance()->activeStatus();

        return view('admin.member.edit', compact('member', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MemberRequest $request, User $member)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.member', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $member->load(['media', 'address']);

            $member = MemberFacade::setModel($member)
                ->setRequest($request)
                ->storeData()
                ->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:member')
                ->causedBy(Auth::user())
                ->performedOn($member)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.members.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:member')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->withErrors($message)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $member)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.member', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module);

        DB::beginTransaction();

        try {

            $member->delete();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');
            $status = 'success';

            activity()->useLog('admin:member')
                ->causedBy(Auth::user())
                ->performedOn($member)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:member')
                ->causedBy(Auth::user())
                ->performedOn($member)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.members.index')
            ])
            ->sendJson();
    }
}
