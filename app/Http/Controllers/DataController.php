<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Product;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\CountryState;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function getCountryStateFromCountry(Country $country)
    {
        $action = Permission::ACTION_READ;
        $status = 'success';

        $country_states = CountryState::where('country_id', $country->id)
            ->orderBy('name', 'asc')
            ->get();

        return Response::instance()
            ->withStatusCode('modules.country_state', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage()
            ->withData($country_states->toArray())
            ->sendJson();
    }

    public function getCityFromCountryState(Country $country, CountryState $country_state)
    {
        $action = Permission::ACTION_READ;
        $status = 'success';

        $cities = City::where('country_state_id', $country_state->id)
            ->whereHas('country', function ($query) use ($country) {
                $query->where((new Country())->getTable() . '.id', $country->id);
            })
            ->orderBy('name', 'asc')
            ->get();

        return Response::instance()
            ->withStatusCode('modules.city', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage()
            ->withData($cities->toArray())
            ->sendJson();
    }

    public function getSkuFromProduct(Product $product)
    {
        $action = Permission::ACTION_READ;
        $status = 'success';

        $sku = $product->productAttributes()
            ->select('id', 'sku', 'product_id')
            ->orderBy('sku', 'asc')
            ->get();

        return Response::instance()
            ->withStatusCode('modules.product', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage()
            ->withData($sku->toArray())
            ->sendJson();
    }
}
