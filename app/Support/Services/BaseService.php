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
        $unit_price =   $this->request->get('unit_price') ?? 0;
        $discount   =   $this->request->get('discount') ?? 0;

        $default_price = $this->model->prices()
            ->where('currency_id', $this->request->get('currency'))
            ->firstOr(function () {
                return new Price();
            });

        $default_price->currency_id             =   $this->request->get('currency');
        $default_price->unit_price              =   $unit_price;
        $default_price->discount                =   $discount;
        $default_price->discount_percentage     =   PriceFacade::calcDiscountPercentage($discount, $unit_price);
        $default_price->selling_price           =   PriceFacade::calcSellingPrice($discount, $unit_price);

        if ($default_price->isDirty()) {
            $this->model->prices()->save($default_price);
        }

        PriceFacade::setParent($this->model)->storeConvertedPrice($default_price);

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

    public function generateReportNo($model, string $column, string $prefix): string
    {
        $prefix .= date('Ymd');
        $number = 0;

        $latest_record = $model::select($column)
            ->where($column, 'like', $prefix . '%')
            ->orderBy($column, 'desc')
            ->first();

        if ($latest_record) {
            $number = intval(str_replace($prefix, '', $latest_record->$column));
        }

        return $prefix . str_pad(strval($number + 1), 5, "0", STR_PAD_LEFT);
    }
}
