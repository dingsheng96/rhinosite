<?php

namespace App\Http\Controllers\Merchant;

use App\Models\User;
use App\Helpers\Status;
use App\Models\Service;
use App\Helpers\Message;
use App\Models\Permission;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MerchantFacade;
use App\Http\Requests\VerificationRequest;

class UserVerificationController extends Controller
{
    public $statuses;

    public function __construct()
    {
        $this->statuses = Status::instance()->verificationStatus();
    }

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
        $user = Auth::user()->load([
            'media' => function ($query) {
                $query->ssm();
            },
            'userDetail',
            'address'
        ]);

        $services   =   Service::orderBy('name', 'asc')->get();

        if ($user->userDetail()->approvedDetails()->exists()) {

            return redirect()->route('merchant.dashboard');
        }

        if ($user->userDetail()->pendingDetails()->exists()) {

            return redirect()->route('merchant.verifications.notify');
        }

        return view('merchant.verification.create', compact('user', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VerificationRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $user = User::when(Auth::user()->is_merchant, function ($query) {
                $query->where('id', Auth::id());
            })->firstOrFail();

            $verification = MerchantFacade::setModel($user)
                ->setRequest($request)
                ->storeData(true)
                ->getModel();

            DB::commit();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($verification)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('verifications.notify');
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new UserDetail())
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
    public function show(User $verification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $verification)
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
    public function update(Request $request, User $verification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $verification)
    {
        //
    }

    public function notify()
    {
        $user = Auth::user()->load(['userDetail']);

        if ($user->userDetail->status == UserDetail::STATUS_PENDING || $user->userDetail->status == UserDetail::STATUS_REJECTED) {

            return view('merchant.verification.notify', compact('user'));
        }

        return redirect()->route('merchant.verifications.create');
    }

    public function resubmit()
    {
        return $this->create();
    }
}
