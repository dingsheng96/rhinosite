<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\DataTables\VerificationDataTable;
use App\Support\Facades\UserDetailFacade;

class VerificationController extends Controller
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
    public function show(UserDetail $verification)
    {
        $documents = Media::ssm()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('verification.show', compact('verification', 'documents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDetail $verification)
    {
        $statuses = $this->statuses;
        $documents = Media::ssm()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('verification.edit', compact('verification', 'statuses', 'documents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDetail $verification)
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
            // verification in user details service
            $verification = UserDetailFacade::setModel($verification)
                ->setRequest($request)
                ->verify();

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
}
