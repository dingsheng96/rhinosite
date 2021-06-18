<?php

namespace App\Http\Controllers\Settings\Country;

use App\Helpers\Message;
use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Settings\Currency;
use Illuminate\Support\Facades\DB;
use App\DataTables\CountryDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings\Country\Country;
use App\Models\Settings\Role\Permission;
use App\DataTables\CountryStateDataTable;
use App\Http\Requests\Settings\Country\CountryRequest;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CountryDataTable $dataTable)
    {
        $currencies = Currency::orderBy('name', 'asc')->get();

        return $dataTable->render('settings.country.index', compact('currencies'));
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
    public function store(CountryRequest $request)
    {
        DB::beginTransaction();

        $action = Permission::ACTION_CREATE;
        $module = strtolower(trans_choice('modules.submodules.country', 1));

        try {

            [
                'name' => $name,
                'currency' => $currency,
                'dial' => $dial
            ] = $request->get('create');

            $country = new Country();
            $country->name          =   $name;
            $country->currency_id   =   $currency;
            $country->dial_code     =   $dial;
            $country->save();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($country)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('settings.countries.index')
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new Country())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()
                ->with('fail', Message::instance()->format($action, $module))
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country, CountryStateDataTable $dataTable)
    {
        return $dataTable->with(['country_id' => $country->id])
            ->render('settings.country.show', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country, CountryStateDataTable $dataTable)
    {
        $currencies = Currency::orderBy('name', 'asc')->get();

        return $dataTable->with(['country_id' => $country->id])
            ->render('settings.country.edit', compact('currencies', 'country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CountryRequest $request, Country $country)
    {
        DB::beginTransaction();

        $action = Permission::ACTION_UPDATE;
        $module = strtolower(trans_choice('modules.submodules.country', 1));

        try {

            $country->name          =   $request->get('name');
            $country->currency_id   =   $request->get('currency');
            $country->dial_code     =   $request->get('dial');

            if ($country->isDirty()) {
                $country->save();
            }

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($country)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('settings.countries.index')
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($country)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()
                ->with('fail', Message::instance()->format($action, $module))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.submodules.country', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $country->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($country)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.country', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('settings.countries.index')
            ])
            ->sendJson();
    }
}
