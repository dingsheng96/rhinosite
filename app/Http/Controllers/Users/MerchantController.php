<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Models\Media;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Models\Category;
use App\Helpers\Response;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\MerchantDataTable;
use App\Support\Facades\MerchantFacade;
use App\Models\Settings\Role\Permission;
use App\Http\Requests\Users\MerchantRequest;

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
    public function show(User $merchant)
    {
        $user_details = $merchant->userDetails()
            ->approvedDetails()
            ->with(['media'])
            ->first();

        $documents = $merchant->media()
            ->ssm()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('users.merchant.show', compact('merchant', 'documents', 'user_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $merchant)
    {
        $user_details = $merchant->userDetails()
            ->approvedDetails()
            ->with(['media'])
            ->first();

        $documents = $merchant->media()
            ->ssm()
            ->orderBy('created_at', 'asc')
            ->get();

        $statuses = Status::instance()->accountStatus();

        $categories = Category::orderBy('name', 'asc')->get();

        return view('users.merchant.edit', compact('merchant', 'documents', 'user_details', 'categories', 'statuses'));
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
        $module     =   strtolower(trans_choice('modules.submodules.merchant', 1));
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

            return redirect()->route('users.merchants.index')->withSuccess($message);
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
    public function destroy($id)
    {
        //
    }
}
