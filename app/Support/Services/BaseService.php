<?php

namespace App\Support\Services;

use App\Models\Media;
use App\Models\Price;
use App\Models\Address;
use App\Helpers\FileManager;
use Illuminate\Http\Request;
use App\Support\Facades\PriceFacade;

class BaseService
{
    public $model, $request;
    public $parent = null;

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

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    protected function storeMedia(Media $media, array $config, $file)
    {
        $store_file = FileManager::instance()
            ->store(
                $config['save_path'],
                $file,
                $media->file_path ?? null,
                $config['filename'] ?? null
            );

        $media->original_filename   =   $config['filename'] ?? null;
        $media->type                =   $config['type'];
        $media->path                =   $config['save_path'] ?? null;
        $media->extension           =   $config['extension'] ?? null;
        $media->size                =   $config['filesize'] ?? null;
        $media->mime                =   $config['filemime'] ?? null;
        $media->properties          =   json_encode($config, JSON_UNESCAPED_UNICODE);
        $media->filename            =   basename($store_file);

        if ($media->exists && $media->isDirty()) {
            $media->save();
        } else {
            $this->model->media()->save($media);
        }

        return $this;
    }

    public function storePrice()
    {
        if ($this->request->has('prices')) {

            $data = $this->request->get('prices');

            foreach ($data as $value) {

                $price = $this->model->prices()
                    ->firstOr(function () {
                        return new Price();
                    });

                $price->currency_id         =   $value['currency'];
                $price->unit_price          =   $value['unit_price'];
                $price->discount            =   $value['discount'] ?? 0;
                $price->discount_percentage =   PriceFacade::calcDiscountPercentage(($value['discount'] ?? 0), $value['unit_price']);
                $price->selling_price       =   $value['unit_price'] - ($value['discount'] ?? 0);

                if ($price->exists && $price->isDirty()) {
                    $price->save();
                } else {
                    $this->model->prices()->save($price);
                }
            }
        }

        return $this;
    }

    public function storeAddress()
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

        return $this;
    }
}
