<?php

namespace App\Support\Services;

use App\Models\Media;
use App\Models\Project;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Helpers\FileManager;

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

        return $this;
    }

    public function storeDetails()
    {
        $this->model->title         =  $this->request->get('title_en');
        $this->model->description   =  $this->request->get('description');
        $this->model->user_id       =  $this->request->get('merchant') ?? auth()->id();
        $this->model->services      =  $this->request->get('services');
        $this->model->materials     =  $this->request->get('materials');
        $this->model->unit_id       =  $this->request->get('unit');
        $this->model->unit_value    =  $this->request->get('unit_value');
        $this->model->published     =  $this->request->has('publish');
        $this->model->slug          =  Str::slug($this->request->get('slug'), '-');

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
                $translation->value         =   $this->request->get('title_' . strtolower($language->code));

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

            throw_if((count($files) + $this->model->media()->image()->count()) > $this->model::MAX_IMAGES, new \Exception(__('labels.files_reached_limit')));

            foreach ($files as $file) {
                $config = [
                    'save_path' =>   Project::STORE_PATH,
                    'type'      =>   Media::TYPE_IMAGE,
                    'filemime'  =>   FileManager::instance()->getMimesType($file->getClientOriginalExtension()),
                    'filename'  =>   $file->getClientOriginalName(),
                    'extension' =>   $file->getClientOriginalExtension(),
                    'filesize'  =>   $file->getSize(),
                ];

                $this->storeMedia(new Media(), $config, $file);
            }
        }

        return $this;
    }
}
