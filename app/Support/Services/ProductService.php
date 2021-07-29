<?php

namespace App\Support\Services;

use App\Models\Media;
use App\Models\Product;
use App\Helpers\FileManager;
use App\Models\ProductAttribute;

class ProductService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Product::class);
    }

    public function storeData()
    {
        $this->storeDetails();
        $this->storeImages();

        return $this;
    }

    public function storeDetails()
    {
        $this->model->name                  =   $this->request->get('name');
        $this->model->description           =   $this->request->get('description');
        $this->model->status                =   $this->request->get('status');
        $this->model->product_category_id   =   $this->request->get('category');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);

        return $this;
    }


    public function storeImages()
    {
        if ($this->request->hasFile('thumbnail')) {

            $file  =   $this->request->file('thumbnail');

            $config = [
                'save_path' =>   Product::STORE_PATH,
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

            throw_if((count($files) + $this->model->media()->image()->count()) > $this->model::MAX_IMAGES, new \Exception(__('messages.files_reached_limit')));

            foreach ($files as $file) {
                $config = [
                    'save_path' =>   Product::STORE_PATH,
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
