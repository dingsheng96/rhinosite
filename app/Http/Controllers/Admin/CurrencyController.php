<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Message;
use App\Models\Currency;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Facades\CurrencyFacade;
use App\DataTables\Admin\CurrencyDataTable;
use App\Http\Requests\Admin\CurrencyRequest;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CurrencyDataTable $dataTable)
    {
        return $dataTable->render('admin.currency.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.currency.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurrencyRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.currency', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $currency = CurrencyFacade::setRequest($request)->storeData()->getModel();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('admin:currency')
                ->causedBy(Auth::user())
                ->performedOn($currency)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('currencies.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:currency')
                ->causedBy(Auth::user())
                ->performedOn(new Currency())
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
    public function show(Currency $currency)
    {
        $currency->load('toCurrencyRates');

        return view('admin.currency.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        $currency->load('toCurrencyRates');

        return view('admin.currency.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CurrencyRequest $request, Currency $currency)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.currency', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $currency = CurrencyFacade::setModel($currency)->setRequest($request)->storeData()->getModel();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('admin:currency')
                ->causedBy(Auth::user())
                ->performedOn($currency)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.currencies.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:currency')
                ->causedBy(Auth::user())
                ->performedOn($currency)
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
    public function destroy(Currency $currency)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.currency', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module);

        try {

            throw_if(
                $currency->countries()->count() > 0,
                new \Exception($message),
            );

            $currency->delete();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('admin:currency')
                ->causedBy(Auth::user())
                ->performedOn($currency)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            $status = 'fail';

            activity()->useLog('admin:currency')
                ->causedBy(Auth::user())
                ->performedOn($currency)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.currency', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.currencies.index')
            ])
            ->sendJson();
    }
}
