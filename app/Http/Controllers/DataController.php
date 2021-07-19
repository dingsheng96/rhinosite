<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Product;
use App\Helpers\Response;
use App\Models\AdsBooster;
use App\Models\Permission;
use App\Models\CountryState;
use App\Models\ProductAttribute;

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

    public function getAdsAvailableDate(ProductAttribute $ads)
    {
        $action = Permission::ACTION_READ;
        $status = 'success';
        $message = null;
        $data = [];

        try {

            throw_if(
                $ads->status == ProductAttribute::STATUS_INACTIVE,
                new \Exception('This ad is not available')
            );

            // get ads details (slot, slot type, total slots per day)
            $slots = $ads->slot;
            $slot_type = $ads->slot_type;
            $total_slots_per_day = $ads->total_slots_per_day;

            // get full dates from now till one month later
            $boost_ads_list = AdsBooster::where('product_attribute_id', $ads->id) // get ads list within one month from today
                ->whereBetween('boosted_at', [today(), today()->addMonth()])
                ->get();

            // filter

        } catch (\Error | \Exception $ex) {

            $status = 'fail';
            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.ads', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data)
            ->sendJson();
    }
}
