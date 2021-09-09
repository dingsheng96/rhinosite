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

            $this->model->load([
                'user.userAdsQuotas' => function ($query) {
                    $query->with([
                        'userAdsQuotaHistories' => function ($query) {
                            $query->latest('created_at')->limit(1);
                        }
                    ]);
                }
            ]);

            $ads = Product::with(['productAttributes', 'adsBoosters'])
                ->where('id', $this->request->get('ads_type'))
                ->whereNotNull('slot_type')->whereNotNull('total_slots')
                ->first();

            // check merchant's ads quota
            $user_ads_quota = $this->model->user->userAdsQuotas->where('product_id', $ads->id)->first();

            throw_if(empty($user_ads_quota) || $user_ads_quota->quantity <= 0, new \Exception(__('messages.insufficient_quota')));

            // get ads's product attributes
            $date_from  =   Carbon::createFromFormat('Y-m-d', $this->request->get('date_from'));
            $date_end   =   $this->getLastDayOfBoosting($ads->slot_type, $date_from);

            $booster_slots = AdsBooster::selectRaw('DATE(boosted_at) as boosted_date, COUNT(boosted_at) as used_slots_count')
                ->whereBetween('boosted_at', [$date_from, $date_end])
                ->where('product_id', $ads->id)
                ->groupBy('boosted_date')
                ->get();

            foreach ($booster_slots as $slot) {
                throw_if($slot->used_slots_count >= $ads->total_slots, new \Exception(__('messages.no_slot_available')));
            }

            // create boosters
            for ($day = 0; $day < $date_end->diffInDays($date_from); $day++) {

                $this->model->adsBoosters()->create([
                    'product_id' => $ads->id,
                    'boosted_at' => $date_from->copy()->addDays($day)
                ]);
            }

            // deduct merchant's ads quota by 1
            $user_ads_quota->decrement('quantity');

            // create ads quotas history
            $process_quantity   =   '-1';
            $initial_quantity   =   optional($user_ads_quota->userAdsQuotaHistories->first())->remaining_quantity ?? 0; // latest user ads quota history

            $user_ads_quota->userAdsQuotaHistories()->create([
                'initial_quantity'    =>   $initial_quantity,
                'process_quantity'    =>   $process_quantity,
                'remaining_quantity'  =>   $initial_quantity + $process_quantity,
                'sourceable_type'     =>   get_class($user_ads_quota),
                'sourceable_id'       =>   $user_ads_quota->id,
                'applicable_type'     =>   get_class($this->model),
                'applicable_id'       =>   $this->model->id,
            ]);
        }

        return $this;
    }

    private function getLastDayOfBoosting(string $slot_type, $start_date)
    {
        if ($slot_type == Product::SLOT_TYPE_DAILY) { // calculate the last date from start date of boosting

            $date_end = $start_date->copy()->addDay();
        } elseif ($slot_type == Product::SLOT_TYPE_WEEKLY) {

            $date_end = $start_date->copy()->addWeek();
        } elseif ($slot_type == Product::SLOT_TYPE_MONTHLY) {

            $date_end = $start_date->copy()->addMonth();
        }

        return $date_end;
    }
}
