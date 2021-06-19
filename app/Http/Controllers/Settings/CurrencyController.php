<?php

namespace App\Http\Controllers\Settings;

use Exception;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Settings\Currency;
use Illuminate\Support\Facades\DB;
use App\DataTables\CountryDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\CurrencyDataTable;
use App\Models\Settings\Role\Permission;
use App\Http\Requests\Settings\CurrencyRequest;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CurrencyDataTable $dataTable)
    {
        return $dataTable->render('settings.currency.index');
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
    public function store(CurrencyRequest $request)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.submodules.currency', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $input = $request->get('create');

            $currency = Currency::create([
                'name' => $input['name'],
                'code' => $input['code']
            ]);

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($currency)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('settings.currencies.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
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
    public function show(Currency $currency, CountryDataTable $dataTable)
    {
        return $dataTable->with(['currency' => $currency])
            ->render('settings.currency.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(CurrencyRequest $request, Currency $currency)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.submodules.currency', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $input = $request->get('update');

            $currency->name = $input['name'];
            $currency->code = $input['code'];

            if ($currency->isDirty()) {
                $currency->save();
            }

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($currency)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('settings.currencies.index')
                ->withSuccess(Message::instance()->format($action, $module, 'success'));
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
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
        $module     =   strtolower(trans_choice('modules.submodules.currency', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module);

        try {

            throw_if(
                $currency->countries()->count() > 0,
                new Exception($message),
            );

            $currency->delete();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($currency)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            $status = 'fail';

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($currency)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.currency', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('settings.countries.index')
            ])
            ->sendJson();
    }
}
