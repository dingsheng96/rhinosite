<?php

namespace App\Support\Services;

use App\Models\User;
use App\Models\Media;
use App\Helpers\FileManager;
use App\Support\Services\BaseService;

class MemberService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeData()
    {
        $this->storeProfile();
        $this->storeAddress();
        $this->storeImage();

        return $this;
    }

    public function storeProfile()
    {
        $this->model->name      =   $this->request->get('name');
        $this->model->phone     =   $this->request->get('phone');
        $this->model->email     =   $this->request->get('email');
        $this->model->status    =   $this->request->get('status', User::STATUS_ACTIVE);
        $this->model->password  =   $this->request->get('password');
        $this->model->type      =   User::TYPE_MEMBER;

        if ($this->model->isDirty()) {
            $this->model->save();
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

            $media = $this->model->media()->logo()->firstOr(function () {
                return new Media();
            });

            return $this->storeMedia($media, $config, $file);
        }

        return $this;
    }

    public function verifiedEmail(bool $status = false)
    {
        if ($status) {
            $this->model->email_verified_at = now();
            $this->model->save();
        }

        return $this;
    }
}
