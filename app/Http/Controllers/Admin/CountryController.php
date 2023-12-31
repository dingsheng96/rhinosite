<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Helpers\Message;
use App\Models\Currency;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Admin\CountryDataTable;
use App\Http\Requests\Admin\CountryRequest;
use App\DataTables\Admin\CountryStateDataTable;

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

        return $dataTable->render('admin.country.index', compact('currencies'));
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

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.country', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $input = $request->get('create');

            $country = new Country();
            $country->name          =   $input['name'];
            $country->currency_id   =   $input['currency'];
            $country->dial_code     =   $input['dial'];

            if (!empty($input['set_default'])) {
                Country::defaultCountry()->first()->update([
                    'set_default' => false
                ]);

                $country->set_default = true;
            }

            $country->save();

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('admin:country')
                ->causedBy(Auth::user())
                ->performedOn($country)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.countries.index')
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:country')
                ->causedBy(Auth::user())
                ->performedOn(new Country())
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
    public function show(Country $country, CountryStateDataTable $dataTable)
    {
        return $dataTable->with(['country_id' => $country->id])
            ->render('admin.country.show', compact('country'));
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
            ->render('admin.country.edit', compact('currencies', 'country'));
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

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.country', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $country->name          =   $request->get('name');
            $country->currency_id   =   $request->get('currency');
            $country->dial_code     =   $request->get('dial');

            if ($request->has('set_default')) {
                Country::defaultCountry()->first()->update([
                    'set_default' => false
                ]);

                $country->set_default = true;
            }

            if ($country->isDirty()) {
                $country->save();
            }

            DB::commit();

            $status =   'success';
            $message = Message::instance()->format($action, $module, $status);

            activity()->useLog('admin:country')
                ->causedBy(Auth::user())
                ->performedOn($country)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('admin.countries.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('admin:country')
                ->causedBy(Auth::user())
                ->performedOn($country)
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
    public function destroy(Country $country)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.country', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $country->delete();

        activity()->useLog('admin:country')
            ->causedBy(Auth::user())
            ->performedOn($country)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.country', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.countries.index')
            ])
            ->sendJson();
    }
}
