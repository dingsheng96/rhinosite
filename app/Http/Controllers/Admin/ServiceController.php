<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Admin\ServiceDataTable;
use App\Http\Requests\Admin\ServiceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ServiceDataTable $dataTable)
    {
        return $dataTable->render('admin.service.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.service', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $input = $request->get('create');

            $service = Service::create([
                'name'          => $input['name'],
                'description'   => $input['description']
            ]);

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('admin:service')
                ->causedBy(Auth::user())
                ->performedOn($service)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.services.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:service')
                ->causedBy(Auth::user())
                ->performedOn(new Service())
                ->withProperties($request->all())
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
    public function show(Service $service)
    {
        return view('admin.service.show', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.service', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $input = $request->get('update');

            $service->name         = $input['name'];
            $service->description  = $input['description'];

            if ($service->isDirty()) {
                $service->save();
            }

            DB::commit();

            $status = 'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:service')
                ->causedBy(Auth::user())
                ->performedOn($service)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.services.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:service')
                ->causedBy(Auth::user())
                ->performedOn($service)
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
    public function destroy(Service $service)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.service', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module);

        try {

            $service->loadCount(['userDetails']);

            throw_if($service->user_details_count > 0, new \Exception('Unable to delete! The service is being used.'));

            $service->delete();

            $message = Message::instance()->format($action, $module, 'success');
            $status = 'success';

            activity()->useLog('admin:service')
                ->causedBy(Auth::user())
                ->performedOn($service)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:service')
                ->causedBy(Auth::user())
                ->performedOn($service)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.service', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.services.index')
            ])
            ->sendJson();
    }
}
