<?php

namespace App\Http\Controllers\Merchant;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Models\Product;
use App\Models\Project;
use App\Helpers\Response;
use App\Models\AdsBooster;
use App\Models\Permission;
use App\Models\CountryState;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

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

    public function getAdsUnavailableDate(Request $request, Product $ads)
    {
        $action     =   Permission::ACTION_READ;
        $status     =   'success';
        $message    =   'Ok';
        $data       =   [];

        try {

            $ads->load(['productAttributes', 'adsBoosters']);

            throw_if(
                $ads->status == Product::STATUS_INACTIVE,
                new \Exception('This ads is not available')
            );

            // get all unavailable dates (included full slots and the same date of the project boosting with same ads)
            $boost_ads_list = AdsBooster::selectRaw('DATE(boosted_at) as boosted_date, COUNT(boosted_at) as used_slots_count')
                ->where('product_id', $ads->id)
                ->whereDate('boosted_at', '>',  today())
                ->groupBy('boosted_date')
                ->orderBy('boosted_date');

            $filtered_boost_ads_list = (clone $boost_ads_list)->having('used_slots_count', '>=', $ads->total_slots)->get();

            if (!empty($request->get('project'))) {
                $filtered_boost_ads_list = (clone $boost_ads_list)->whereHasMorph('boostable', [Project::class], function (Builder $query) use ($request) {
                    $query->where('id', $request->get('project'));
                })->get()->each(function ($taken_slot) use ($filtered_boost_ads_list) {
                    $filtered_boost_ads_list->push($taken_slot);
                });
            }

            foreach ($filtered_boost_ads_list as $list) {

                if (count($data) < 1) {
                    $data[] = today()->toDateString();
                    continue;
                }

                $end_date       =   Carbon::createFromFormat('Y-m-d', end($data));
                $listed_date    =   Carbon::createFromFormat('Y-m-d', $list->boosted_date);
                $periods        =   [];

                if ($ads->slot_type == Product::SLOT_TYPE_DAILY) {

                    $difference = $end_date->diffInDays($listed_date);
                } elseif ($ads->slot_type == Product::SLOT_TYPE_WEEKLY) {

                    $difference = $end_date->diffInWeeks($listed_date);
                } elseif ($ads->slot_type == Product::SLOT_TYPE_MONTHLY) {

                    $difference = $end_date->diffInMonths($listed_date);
                }

                if ($difference <= 1) { // check the difference of the boosting date and the end date (if <= 1, get the difference period)

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
            ->withData(array_unique($data))
            ->sendJson();
    }

    public function getMerchantProjects(User $merchant)
    {
        $action     =   Permission::ACTION_READ;
        $status     =   'success';
        $message    =   'Ok';
        $data       =   [];

        $merchant->load([
            'projects' => function ($query) {
                $query->published();
            }
        ]);

        $data = $merchant->projects;

        return Response::instance()
            ->withStatusCode('modules.user', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data->toArray())
            ->sendJson();
    }

    public function getMerchantAdsQuota(User $merchant)
    {
        $action     =   Permission::ACTION_READ;
        $status     =   'success';
        $message    =   'Ok';
        $data       =   [];

        $merchant->load([
            'userAdsQuotas' => function ($query) {
                $query->where('quantity', '>', 0);
            }
        ]);

        $data = $merchant->userAdsQuotas->map(function ($item) {
            return [
                'name'  =>  $item->product->name,
                'id'    =>  $item->product->id,
            ];
        });

        return Response::instance()
            ->withStatusCode('modules.user', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data->toArray())
            ->sendJson();
    }
}
