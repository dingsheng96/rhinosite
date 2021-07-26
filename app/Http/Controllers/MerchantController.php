<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\MerchantDataTable;
use App\Http\Requests\MerchantRequest;
use App\Support\Facades\MerchantFacade;

class MerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:merchant.read'])->only(['index', 'show']);
        $this->middleware(['can:merchant.create'])->only(['create', 'store']);
        $this->middleware(['can:merchant.update'])->only(['edit', 'update']);
        $this->middleware(['can:merchant.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MerchantDataTable $dataTable)
    {
        return $dataTable->render('merchant.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Status::instance()->activeStatus();

        return view('merchant.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $merchant = MerchantFacade::setRequest($request)->storeData()->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('merchants.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new User())
                ->withProperties($request->all())
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
    public function show(User $merchant)
    {
        $user_details = $merchant->userDetail()
            ->approvedDetails()
            ->with(['media'])
            ->first();

        $documents = $merchant->media()
            ->ssm()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('merchant.show', compact('merchant', 'documents', 'user_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $merchant)
    {
        $user_details = $merchant->userDetail()
            ->approvedDetails()
            ->with(['media'])
            ->first();

        $documents = $merchant->media()
            ->ssm()
            ->orderBy('created_at', 'asc')
            ->get();

        $statuses = Status::instance()->activeStatus();

        return view('merchant.edit', compact('merchant', 'documents', 'user_details', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantRequest $request, User $merchant)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $merchant = MerchantFacade::setModel($merchant)
                ->setRequest($request)
                ->storeData()
                ->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('merchants.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
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
    public function destroy(User $merchant)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module);

        try {

            $merchant->delete();

            $message = Message::instance()->format($action, $module, 'success');
            $status = 'success';

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($merchant)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchants.index')
            ])
            ->sendJson();
    }
}
