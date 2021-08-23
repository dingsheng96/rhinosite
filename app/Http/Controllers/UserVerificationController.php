<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Status;
use App\Models\Package;
use App\Models\Service;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MerchantFacade;
use App\DataTables\VerificationDataTable;
use App\Support\Facades\UserDetailFacade;
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
    public function index(VerificationDataTable $dataTable)
    {
        return $dataTable->render('verification.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user       =   Auth::user()->load([
            'media' => function ($query) {
                $query->ssm();
            },
            'userDetail',
            'address'
        ]);

        $services   =   Service::orderBy('name', 'asc')->get();

        if ($user->userDetail()->approvedDetails()->exists()) {

            return redirect()->route('dashboard');
        }

        if ($user->userDetail()->pendingDetails()->exists()) {

            return redirect()->route('verifications.notify');
        }

        return view('verification.create', compact('user', 'services'));
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
        $verification->load([
            'media' => function ($query) {
                $query->ssm()->orWhere(function ($query) {
                    $query->logo();
                });
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
                $query->ssm()->orWhere(function ($query) {
                    $query->logo();
                });
            },
            'userDetail',
            'address'
        ]);

        // trial plans
        $plans = ProductAttribute::whereHas('product', function ($query) {
            $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
        })->trialMode(true)->get();

        return view('verification.edit', compact('verification', 'statuses', 'plans'));
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
            ],
            'trial' =>  ['nullable', Rule::exists(ProductAttribute::class, 'id')->where('trial_mode', true)]
        ]);

        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.verification', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $verification = $verification->load('userDetail');

            // verification in user details service
            $verification = UserDetailFacade::setModel($verification->userDetail)
                ->setRequest($request)
                ->verify()
                ->getModel();

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
    public function destroy(User $verification)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.verification', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        DB::beginTransaction();

        try {

            $verification->delete();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($verification)
                ->log($message);

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            $message = $e->getMessage();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($verification)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.user', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('verifications.index')
            ])
            ->sendJson();
    }

    public function notify()
    {
        $user = Auth::user()->load(['userDetail']);

        if ($user->userDetail()->where(function ($query) {
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
        return $this->create();
    }
}
