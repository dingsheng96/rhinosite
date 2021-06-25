<?php

namespace App\Http\Controllers\Settings;

use App\Models\Country;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Helpers\FileManager;
use App\Models\CountryState;
use App\DataTables\CityDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Settings\Country\CountryStateImport;
use App\Imports\Settings\Country\CountryStateCityImport;
use App\Http\Requests\Settings\Country\CountryStateRequest;

class CountryStateController extends Controller
{
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryStateRequest $request, Country $country)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.submodules.country_state', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $country_state = new CountryState();

            if (!empty($request->get('create')['name'])) {
                $country_state->name = $request->get('create')['name'];
                $country->countryStates()->save($country_state);
            } else if ($request->hasFile('create.file')) {

                $file = $request->file('create')['file'];

                if ($request->has('create.withCity')) {
                    Excel::import(new CountryStateCityImport($country), $file, null, FileManager::instance()->getExcelReaderType($file->extension()));
                } else {
                    Excel::import(new CountryStateImport($country), $file, null, FileManager::instance()->getExcelReaderType($file->extension()));
                }
            }

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($country_state)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('settings.countries.index')
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new CountryState())
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country, CountryState $country_state, CityDataTable $dataTable)
    {
        return $dataTable->with(['country_id' => $country->id, 'country_state_id' => $country_state->id])
            ->render('settings.country.country_state.edit', compact('country', 'country_state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CountryStateRequest $request, Country $country, CountryState $country_state)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.submodules.country_state', 1));
        $message    =   Message::instance()->format($action, $module);

        try {

            $country_state->name = $request->get('name');
            if ($country_state->isDirty()) {
                $country_state->save();
            }

            DB::commit();

            $message = Message::instance()->format($action, $module, 'success');

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($country_state)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('settings.countries.edit', ['country' => $country->id])
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($country_state)
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
    public function destroy(Country $country, CountryState $country_state)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.submodules.country_state', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $country_state->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($country)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.country_state', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('settings.countries.index')
            ])
            ->sendJson();
    }
}
