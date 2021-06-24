<?php

namespace App\Support\Services;

use App\Helpers\FileManager;
use Illuminate\Http\Request;

class BaseService
{
    public $model, $request;

    public function __construct($model)
    {
        $this->model = new $model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getModel()
    {
        $this->model->refresh();

        return $this->model;
    }

    public function storeMedia($media, string $store_as, string $save_path)
    {
        $config = [
            'save_path' => $save_path,
            'type'      => $store_as
        ];

        if (is_array($media)) {
            $this->storeMultipleMedia($media, $config);
        } else {
            $this->storeSingleMedia($media, $config);
        }

        return $this;
    }

    private function storeMultipleMedia($media, array $config): void
    {
        foreach ($media as $image) {
            $this->storeSingleMedia($image, $config);
        }
    }

    private function storeSingleMedia($media, array $config): void
    {
        $config = array_merge($config, [
            'filename'  =>   $media->getClientOriginalName(),
            'extension' =>   $media->getClientOriginalExtension(),
            'filesize'  =>   $media->getSize(),
            'filemime'  =>   FileManager::instance()
                ->getMimesType($media->getClientOriginalExtension())
        ]);

        FileManager::instance()->store(
            $config['save_path'],
            $media,
            $config['filename']
        );

        $this->model->media()->create([
            'type'                =>   $config['type'],
            'original_filename'   =>   $config['filename'],
            'filename'            =>   $config['filename'],
            'path'                =>   $config['save_path'],
            'extension'           =>   $config['extension'],
            'size'                =>   $config['filesize'],
            'mime'                =>   $config['filemime'],
            'properties'          =>   json_encode($config, JSON_UNESCAPED_UNICODE)
        ]);
    }
}
