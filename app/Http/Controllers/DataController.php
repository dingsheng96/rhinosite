<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Product;
use App\Helpers\Response;
use App\Models\AdsBooster;
use App\Models\Permission;
use App\Models\CountryState;
use Illuminate\Support\Carbon;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

    public function getAdsUnavailableDate(Product $ads)
    {
        $action     =   Permission::ACTION_READ;
        $status     =   'success';
        $message    =   'Ok';
        $data       =   [];

        try {

            $ads->load([
                'productAttributes' => function ($query) {
                    $query->orderBy('slot');
                }
            ]);

            throw_if(
                $ads->status == Product::STATUS_INACTIVE,
                new \Exception('This ads is not available')
            );

            // get attribute
            $attribute = $ads->productAttributes
                ->map(function ($item) {
                    return ['slot' => $item->slot, 'slot_type' => $item->slot_type];
                })
                ->unique()->first();

            // get full dates from now till one month later
            $boost_ads_list = AdsBooster::selectRaw('DATE(boosted_at) as boosted_date, COUNT(boosted_at) as day_count')
                ->whereDate('boosted_at', '>',  today())
                ->where('product_id', $ads->id)
                ->groupBy(DB::raw('DATE(boosted_at)'))
                ->having('day_count', '=', $attribute['slot'])
                ->get();

            switch ($attribute['slot_type']) {
                case ProductAttribute::SLOT_TYPE_DAILY:
                    $difference = 1;
                    break;
                case ProductAttribute::SLOT_TYPE_WEEKLY:
                    $difference = 7;
                    break;
                case ProductAttribute::SLOT_TYPE_MONTHLY:
                    $difference = 30;
                    break;
                default:
                    $difference = 1;
                    break;
            }

            foreach ($boost_ads_list as $list) {

                if (count($data) < 1) {
                    $data[] = $list->boosted_date;
                    continue;
                }

                $end_date       =   Carbon::createFromFormat('Y-m-d', end($data));
                $listed_date    =   Carbon::createFromFormat('Y-m-d', $list->boosted_date);
                $periods        =   [];

                if ($end_date->diffInDays($listed_date) <= $difference) {
                    $period = $end_date->daysUntil($listed_date)->toArray();

                    foreach ($period as $date) {
                        $periods[] = $date->toDateString();
                    }
                }

                $data = array_merge($data, $periods);
            }
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
