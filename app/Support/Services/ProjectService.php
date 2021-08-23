<?php

namespace App\Support\Services;

use Carbon\Carbon;
use App\Models\Media;
use App\Models\Product;
use App\Models\Project;
use App\Models\Language;
use App\Models\AdsBooster;
use App\Models\Translation;
use App\Helpers\FileManager;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Models\UserAdsQuotaHistory;
use App\Support\Services\BaseService;

class ProjectService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Project::class);
    }

    public function storeData()
    {
        $this->storeDetails();
        $this->storeTitle();
        $this->storeImages();
        $this->storePrice();
        $this->storeAddress();
        $this->storeBoostAds();

        return $this;
    }

    public function storeDetails()
    {
        $this->model->title         =  $this->request->get('title_en');
        $this->model->description   =  $this->request->get('description');
        $this->model->user_id       =  $this->request->get('merchant') ?? auth()->id();
        $this->model->materials     =  $this->request->get('materials');
        $this->model->unit_id       =  $this->request->get('unit');
        $this->model->unit_value    =  $this->request->get('unit_value');
        $this->model->status        =  $this->request->get('status');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);

        return $this;
    }

    public function storeTitle()
    {
        $languages = Language::orderBy('name', 'asc')->get();

        foreach ($languages as $language) {
            if ($this->request->has('title_' . strtolower($language->code))) {

                $translation = $this->model->translations()
                    ->where('language_id', $language->id)
                    ->firstOr(function () {
                        return new Translation();
                    });

                $translation->language_id   =   $language->id;
                $translation->value         =   $this->request->get('title_' . strtolower($language->code), '');

                if ($translation->exists && $translation->isDirty()) {
                    $translation->save();
                } else {
                    $this->model->translations()->save($translation);
                }
            }
        }

        return $this;
    }

    public function storeImages()
    {
        if ($this->request->hasFile('thumbnail')) {

            $file  =   $this->request->file('thumbnail');

            $config = [
                'save_path' =>   Project::STORE_PATH,
                'type'      =>   Media::TYPE_THUMBNAIL,
                'filemime'  =>   FileManager::instance()->getMimesType($file->getClientOriginalExtension()),
                'filename'  =>   $file->getClientOriginalName(),
                'extension' =>   $file->getClientOriginalExtension(),
                'filesize'  =>   $file->getSize(),
            ];

            $media = $this->model->media()->thumbnail()
                ->firstOr(function () {
                    return new Media();
                });

            $this->storeMedia($media, $config, $file);
        }

        if ($this->request->hasFile('files')) {

            $files = $this->request->file('files');

            throw_if((count($files) + $this->model->media()->image()->count()) > Media::MAX_IMAGE_PROJECT, new \Exception(__('messages.files_reached_limit')));

            foreach ($files as $file) {
                $config = [
                    'save_path' =>   Project::STORE_PATH,
                    'type'      =>   Media::TYPE_IMAGE,
                    'filemime'  =>   FileManager::instance()->getMimesType($file->getClientOriginalExtension()),
                    'filename'  =>   $file->getClientOriginalName(),
                    'extension' =>   $file->getClientOriginalExtension(),
                    'filesize'  =>   $file->getSize(),
                ];

                $media = $this->storeMedia(new Media(), $config, $file);
            }
        }

        return $this;
    }

    public function storeBoostAds()
    {
        if ($this->request->has('ads_type')) {

            $this->model->load(['user.userAdsQuotas']);

            $ads = Product::with([
                'productAttributes' => function ($query) {
                    $query->select('product_id', 'slot', 'slot_type')->first();
                }
            ])
                ->where('id', $this->request->get('ads_type'))
                ->whereHas('productCategory', function ($query) {
                    $query->where('name', ProductCategory::TYPE_ADS);
                })
                ->first();

            // check merchant's ads quota
            $user       =   $this->model->user;

            $ads_quota  =   $user->userAdsQuotas()
                ->with([
                    'userAdsQuotaHistories' => function ($query) {
                        $query->orderByDesc('created_at')->first();
                    }
                ])->where('product_id', $ads->id)->first();

            throw_if(empty($ads_quota) || $ads_quota->quantity <= 0, new \Exception(__('messages.insufficient_quota')));

            // get ads's product attributes
            $date_from = Carbon::createFromFormat('Y-m-d', $this->request->get('date_from'));

            switch ($ads->productAttributes->first()->slot_type) {
                case ProductAttribute::SLOT_TYPE_MONTHLY:
                    $date_end = $date_from->copy()->addMonth();
                    $total_boosting = 30;
                    break;
                case ProductAttribute::SLOT_TYPE_WEEKLY:
                    $date_end = $date_from->copy()->addWeek();
                    $total_boosting = 7;
                    break;
                default:
                    $date_end = $date_from->copy()->addDay();
                    $total_boosting = 1;
                    break;
            }

            $booster_slots = AdsBooster::selectRaw('DATE(boosted_at) as boosted_date, COUNT(boosted_at) as day_count')
                ->whereBetween('boosted_at', [$date_from, $date_end])
                ->where('product_id', $ads->id)
                ->groupBy(DB::raw('DATE(boosted_at)'))
                ->get();

            foreach ($booster_slots as $slot) {
                throw_if($slot->day_count >= $ads->productAttributes->first()->slot, new \Exception(__('messages.no_slot_available')));
            }

            // create boosters
            for ($day = 0; $day < $total_boosting; $day++) {
                $ads_booster = new AdsBooster();
                $ads_booster->product_id = $ads->id;
                $ads_booster->boosted_at = $date_from->copy()->addDays($day);
                $this->model->adsBoosters()->save($ads_booster);
            }

            // decrement merchant's ads quota
            $ads_quota->decrement('quantity');

            // create ads quotas history
            $latest_history     =   $ads_quota->userAdsQuotaHistories->first();
            $initial_quantity   =   $latest_history->remaining_quantity;
            $process_quantity   =   '-1';

            $ads_quota_history  =   new UserAdsQuotaHistory();
            $ads_quota_history->initial_quantity    =   $initial_quantity;
            $ads_quota_history->process_quantity    =   $process_quantity;
            $ads_quota_history->remaining_quantity  =   $initial_quantity + $process_quantity;
            $ads_quota_history->sourceable_type     =   get_class($ads_quota);
            $ads_quota_history->sourceable_id       =   $ads_quota->id;
            $ads_quota_history->applicable_type     =   get_class($this->model);
            $ads_quota_history->applicable_id       =   $this->model->id;
            $ads_quota->userAdsQuotaHistories()->save($ads_quota_history);
        }

        return $this;
    }
}
