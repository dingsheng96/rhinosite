<?php

namespace App\Support\Services;

use App\Helpers\Misc;
use App\Models\Media;
use App\Models\Address;
use App\Models\Project;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Helpers\FileManager;
use App\Models\Settings\Currency;

class ProjectService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Project::class);
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

            FileManager::instance()->removeAndStore(
                $config['save_path'],
                $file,
                $media->file_path ?? null,
                $config['filename']
            );

            $media->type                =   $config['type'];
            $media->original_filename   =   $config['filename'];
            $media->filename            =   $config['filename'];
            $media->path                =   $config['save_path'];
            $media->extension           =   $config['extension'];
            $media->size                =   $config['filesize'];
            $media->mime                =   $config['filemime'];
            $media->properties          =   json_encode($config, JSON_UNESCAPED_UNICODE);

            if ($media->exists && $media->isDirty()) {
                $media->save();
            } else {
                $this->model->media()->save($media);
            }
        }

        if ($this->request->hasFile('files')) {

            $this->storeMedia($this->request->file('files'), Media::TYPE_IMAGE, Project::STORE_PATH);
        }
    }
}
