<?php

namespace App\Support\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Media;
use App\Models\UserDetail;
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

    public function storeDetails(bool $from_verification = false)
    {
        $details = $this->model->userDetails()
            ->when($from_verification, function ($query) {
                $query->pendingDetails();
            })
            ->when(!$from_verification, function ($query) {
                $query->approvedDetails();
            })
            ->firstOr(function () {
                return new UserDetail();
            });

        $details->business_since        =   $this->request->get('business_since');
        $details->website               =   $this->request->get('website');
        $details->facebook              =   $this->request->get('facebook');
        $details->pic_name              =   $this->request->get('pic_name');
        $details->pic_phone             =   $this->request->get('pic_phone');
        $details->pic_email             =   $this->request->get('pic_email');

        // new details
        if (!$details->exists) {
            $details->status = UserDetail::STATUS_PENDING;
        }

        if ($details->isDirty()) {
            $this->model->userDetails()->save($details);
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

            return $this->storeMedia($media, $config, $file);
        }

        return $this;
    }

    public function storeSsmCert()
    {
        if ($this->request->hasFile('ssm_cert')) {

            $file = $this->request->file('ssm_cert');

            $config = [
                'save_path'     =>   User::STORE_PATH,
                'type'          =>   Media::TYPE_SSM,
                'filemime'      =>   FileManager::instance()->getMimesType($file->getClientOriginalExtension()),
                'filename'      =>   $file->getClientOriginalName(),
                'extension'     =>   $file->getClientOriginalExtension(),
                'filesize'      =>   $file->getSize(),
            ];

            $media = $this->model->media()->ssm()
                ->firstOr(function () {
                    return new Media();
                });

            return $this->storeMedia($media, $config, $file);
        }

        return $this;
    }
}
