<?php

namespace App\Support\Services;

use App\Models\User;
use App\Helpers\Misc;
use App\Models\Media;
use App\Models\Address;
use App\Models\Project;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Helpers\FileManager;
use App\Models\Settings\Currency;

class MerchantService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeData()
    {
        $this->saveProject();
        $this->saveTitle();
        $this->saveLocation();
        $this->saveMedia();

        return $this;
    }

    public function saveProject()
    {
        $currency = Currency::whereHas('countries', function ($query) {
            $query->where('id', $this->request->get('country'));
        })->first();

        $this->model->title         =  $this->request->get('title_en');
        $this->model->description   =  $this->request->get('description');
        $this->model->user_id       =  $this->request->get('user') ?? auth()->id();
        $this->model->services      =  $this->request->get('servics');
        $this->model->materials     =  $this->request->get('materials');
        $this->model->currency_id   =  $currency->id;
        $this->model->unit_price    =  Misc::instance()->getPriceFromFloatToInt($this->request->get('unit_price'));
        $this->model->unit_id       =  $this->request->get('unit');
        $this->model->unit_value    =  $this->request->get('unit_value');
        $this->model->published     =  $this->request->has('publish');
        $this->model->slug          =  Str::slug($this->request->get('slug'), '-');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);
    }

    public function saveLocation()
    {
        $address = $this->model->address()
            ->firstOr(function () {
                return new Address();
            });

        $address->address_1 = $this->request->get('address_1');
        $address->address_2 = $this->request->get('address_2');
        $address->postcode  = $this->request->get('postcode');
        $address->city_id   = $this->request->get('city');

        if ($address->exists && $address->isDirty()) {
            $address->save();
        } else {
            $this->model->address()->save($address);
        }
    }

    public function saveTitle()
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
                $translation->value         =   $this->request->get('title_' . strtolower($language->code));

                if ($translation->exists && $translation->isDirty()) {
                    $translation->save();
                } else {
                    $this->model->translations()->save($translation);
                }
            }
        }
    }

    public function saveMedia()
    {
        if ($this->request->hasFile('thumbnail')) {

            $thumbnail  =   $this->request->file('thumbnail');

            $setup = [
                'save_path' =>   Project::MEDIA_THUMBNAIL_PATH,
                'filename'  =>   $thumbnail->getClientOriginalName(),
                'extension' =>   $thumbnail->getClientOriginalExtension(),
                'filesize'  =>   $thumbnail->getSize(),
                'filetype'  =>   Media::TYPE_THUMBNAIL,
                'filemime'  =>   FileManager::instance()->getMimesType($thumbnail->getClientOriginalExtension())
            ];

            $media = $this->model->media()
                ->where('type', Media::TYPE_THUMBNAIL)
                ->firstOr(function () {
                    return new Media();
                });

            FileManager::instance()->removeAndStore(
                $setup['save_path'],
                $thumbnail,
                $media->file_path ?? null,
                $setup['filename']
            );

            $media->type                =   $setup['filetype'];
            $media->original_filename   =   $setup['filename'];
            $media->filename            =   $setup['filename'];
            $media->path                =   $setup['save_path'];
            $media->extension           =   $setup['extension'];
            $media->size                =   $setup['filesize'];
            $media->mime                =   $setup['filemime'];
            $media->properties          =   json_encode($setup, JSON_UNESCAPED_UNICODE);

            if ($media->exists && $media->isDirty()) {
                $media->save();
            } else {
                $this->model->media()->save($media);
            }
        }

        if ($this->request->hasFile('files')) {

            $images     =   $this->request->file('files');
            $save_path  =   Project::MEDIA_IMAGE_PATH;

            foreach ($images as $image) {

                $setup = [
                    'save_path' =>   Project::MEDIA_IMAGE_PATH,
                    'filename'  =>   $image->getClientOriginalName(),
                    'extension' =>   $image->getClientOriginalExtension(),
                    'filesize'  =>   $image->getSize(),
                    'filetype'  =>   Media::TYPE_IMAGE,
                    'filemime'  =>   FileManager::instance()->getMimesType($image->getClientOriginalExtension())
                ];

                FileManager::instance()->store(
                    $save_path,
                    $image,
                    $setup['filename']
                );

                $media = new Media();
                $media->type                =   $setup['filetype'];
                $media->original_filename   =   $setup['filename'];
                $media->filename            =   $setup['filename'];
                $media->path                =   $setup['save_path'];
                $media->extension           =   $setup['extension'];
                $media->size                =   $setup['filesize'];
                $media->mime                =   $setup['filemime'];
                $media->properties          =   json_encode($setup, JSON_UNESCAPED_UNICODE);

                $this->model->media()->save($media);
            }
        }
    }
}
