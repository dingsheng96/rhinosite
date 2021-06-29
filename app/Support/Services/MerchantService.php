<?php

namespace App\Support\Services;

use App\Models\User;
use App\Models\Media;
use App\Models\UserDetails;
use App\Helpers\FileManager;

class MerchantService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeData()
    {
        $this->storeProfile();
        $this->storeDetails();
        $this->storeCategory();
        $this->storeAddress();
        $this->storeImage();

        return $this;
    }

    public function storeProfile()
    {
        $this->model->name      =  $this->request->get('name');
        $this->model->phone     =  $this->request->get('phone');
        $this->model->email     =  $this->request->get('email');
        $this->model->status    =  $this->request->get('status');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);

        return $this;
    }

    public function storeDetails()
    {
        $details = $this->model->userDetails()
            ->approvedDetails()
            ->firstOr(function () {
                return new UserDetails();
            });

        $details->years_of_experience   =   $this->request->get('experience');
        $details->website               =   $this->request->get('website');
        $details->facebook              =   $this->request->get('facebook');
        $details->pic_name              =   $this->request->get('pic_name');
        $details->pic_phone             =   $this->request->get('pic_phone');
        $details->pic_email             =   $this->request->get('pic_email');

        // new details
        if (!$details->exists) {
            $details->status = UserDetails::STATUS_PENDING;
            $this->model->userDetails()->save($details);

            return $this;
        }

        if ($details->isDirty()) {
            $details->save();
        }

        return $this;
    }

    public function storeImage()
    {
        if ($this->request->hasFile('logo')) {

            $file  =   $this->request->file('logo');

            $config = [
                'save_path'     =>   User::STORE_PATH,
                'type'          =>   Media::TYPE_LOGO,
                'filemime'      =>   FileManager::instance()->getMimesType($file->getClientOriginalExtension()),
                'filename'      =>   $file->getClientOriginalName(),
                'extension'     =>   $file->getClientOriginalExtension(),
                'filesize'      =>   $file->getSize(),
            ];

            $media = $this->model->media()->logo()
                ->firstOr(function () {
                    return new Media();
                });

            return $this->storeMedia($media, $config, $this->request->file('logo'));
        }

        return $this;
    }

    public function storeCategory()
    {
        $category = $this->request->get('category');

        $this->model->categories()
            ->sync(
                is_array($category)
                    ? $category
                    : [$category]
            );

        return $this;
    }
}
