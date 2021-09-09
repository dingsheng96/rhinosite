<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\MerchantFacade;
use App\DataTables\Admin\UserVerificationDataTable;
use App\Http\Requests\Admin\UserVerificationRequest;

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
    public function index(UserVerificationDataTable $dataTable)
    {
        return $dataTable->render('admin.verification.index');
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
            'userDetail', 'address',
            'media' => function ($query) {
                $query->ssm()->orWhere(function ($query) {
                    $query->logo();
                });
            },
        ]);

        return view('admin.verification.show', compact('verification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $verification)
    {
        $verification->load([
            'userDetail', 'address',
            'media' => function ($query) {
                $query->ssm()->orWhere(function ($query) {
                    $query->logo();
                });
            },
        ]);

        $statuses = $this->statuses;

        // trial plans
        $plans = ProductAttribute::whereHas('product', function ($query) {
            $query->filterCategory(ProductCategory::TYPE_SUBSCRIPTION);
        })->trialMode(true)->get();

        return view('admin.verification.edit', compact('verification', 'statuses', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserVerificationRequest $request, User $verification)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.verification', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $verification = $verification->load(['userDetail', 'userSubscriptions']);

            // verification in user details service
            $verification = MerchantFacade::setModel($verification)
                ->setRequest($request)->verifyUserDetail()->getModel();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('admin:verification')
                ->causedBy(Auth::user())
                ->performedOn($verification)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.verifications.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:verification')
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

            activity()->useLog('admin:verification')
                ->causedBy(Auth::user())
                ->performedOn($verification)
                ->log($message);

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            $message = $e->getMessage();

            activity()->useLog('admin:verification')
                ->causedBy(Auth::user())
                ->performedOn($verification)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.user', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.verifications.index')
            ])
            ->sendJson();
    }
}
