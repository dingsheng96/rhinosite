<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Media;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Models\Permission;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MerchantFacade;
use App\DataTables\VerificationDataTable;
use App\Support\Facades\UserDetailFacade;
use Illuminate\Database\Eloquent\Builder;
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
    public function index(Request $request, VerificationDataTable $dataTable)
    {
        return $dataTable->with(['request' => $request])
            ->render('verification.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->userDetail()->approvedDetails()->exists()) {

            return redirect()->route('dashboard');
        }

        if ($user->userDetail()->pendingDetails()->exists()) {

            return redirect()->route('verifications.notify');
        }

        return view('verification.create', compact('user'));
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
                ->storeProfile()
                ->storeDetails(true)
                ->storeSsmCert()
                ->storeAddress()
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
        $verification->load([
            'media' => function ($query) {
                $query->ssm();
            },
            'userDetail',
            'address'
        ]);

        return view('verification.show', compact('verification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $verification)
    {
        $statuses = $this->statuses;

        $verification->load([
            'media' => function ($query) {
                $query->ssm();
            },
            'userDetail',
            'address'
        ]);

        return view('verification.edit', compact('verification', 'statuses'));
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
        $request->validate([
            'status' => [
                'required',
                Rule::in(array_keys($this->statuses))
            ]
        ]);

        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.verification', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $verification = $verification->load('userDetail');

            // verification in user details service
            $verification = UserDetailFacade::setModel($verification->userDetail)->setRequest($request)->verify();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($verification)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('verifications.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($verification)
                ->withProperties($request->all())
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
    public function destroy($id)
    {
        //
    }

    public function notify()
    {
        $user = Auth::user()->load(['userDetail']);

        if ($user->userDetail->where(function ($query) {
            $query->pendingDetails();
        })->orWhere(function ($query) {
            $query->rejectedDetails();
        })->exists()) {

            return view('verification.notify', compact('user'));
        }

        return redirect()->route('verifications.create');
    }

    public function resubmit()
    {
        $user = Auth::user()->load([
            'media' => function ($query) {
                $query->ssm();
            },
            'userDetail',
            'address'
        ]);

        return view('verification.create', compact('user'));
    }
}
