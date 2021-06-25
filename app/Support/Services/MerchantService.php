<?php

namespace App\Support\Services;

use App\Models\User;
use App\Helpers\Misc;
use App\Models\Media;
use App\Models\Address;
use App\Models\Project;
use App\Models\Language;
use App\Models\Translation;
use App\Models\UserDetails;
use Illuminate\Support\Str;
use App\Helpers\FileManager;
use App\Models\Currency;

class MerchantService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeData()
    {
        $this->saveMerchant();
        $this->saveDetails();
        $this->saveCategory();
        $this->saveLocation();
        $this->saveMedia();

        return $this;
    }

    public function saveMerchant()
    {
        $this->model->name      =  $this->request->get('name');
        $this->model->phone     =  $this->request->get('phone');
        $this->model->email     =  $this->request->get('email');
        $this->model->status    =  $this->request->get('status');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);
    }

    public function saveDetails()
    {
        $details = $this->model->userDetails()
            ->approvedDetails()->firstOr(function () {
                return new UserDetails();
            });

        $details->years_of_experience   =   $this->request->get('experience');
        $details->website               =   $this->request->get('website');
        $details->facebook              =   $this->request->get('facebook');
        $details->pic_name              =   $this->request->get('pic_name');
        $details->pic_phone             =   $this->request->get('pic_phone');
        $details->pic_email             =   $this->request->get('pic_email');

        if ($details->exists && $details->isDirty()) {

            $details->save();
        } else {
            // new details
            $details->status = UserDetails::STATUS_PENDING;
            $this->model->userDetails()->save($details);
        }
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

    public function saveMedia()
    {
        if ($this->request->hasFile('logo')) {

            $file  =   $this->request->file('logo');

            $config = [
                'save_path' =>   User::STORE_PATH,
                'type'      =>   Media::TYPE_LOGO,
                'filemime'  =>   FileManager::instance()->getMimesType($file->getClientOriginalExtension()),
                'filename'  =>   $file->getClientOriginalName(),
                'extension' =>   $file->getClientOriginalExtension(),
                'filesize'  =>   $file->getSize(),
            ];

            $media = $this->model->media()->logo()
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
    }

    public function saveCategory()
    {
        $category = $this->request->get('category');

        if (is_array($category)) {

            $this->model->categories()->sync($category);
        } else {
            $this->model->categories()->sync([$category]);
        }
    }
}
