<?php

namespace App\Support\Services;

use App\Models\User;
use App\Models\Media;
use App\Helpers\FileManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\Services\BaseService;

class AccountService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function storeData()
    {
        $this->storeProfile();

        if (Auth::user()->is_merchant) {

            $this->storeDetails();
            $this->storeAddress();
            $this->storeImage();
        }

        return $this;
    }

    public function storeProfile()
    {
        $this->model->name      =  $this->request->get('name');
        $this->model->email     =  $this->request->get('email');
        $this->model->phone     =  $this->request->get('phone');

        if (!empty($this->request->get('new_password'))) {
            $this->model->password = Hash::make($this->request->get('new_password'));
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);
    }

    public function storeDetails()
    {
        $details = $this->model->userDetails()
            ->approvedDetails()
            ->first();

        $details->business_since        =   $this->request->get('business_since');
        $details->website               =   $this->request->get('website');
        $details->facebook              =   $this->request->get('facebook');
        $details->pic_name              =   $this->request->get('pic_name');
        $details->pic_phone             =   $this->request->get('pic_phone');
        $details->pic_email             =   $this->request->get('pic_email');

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

            $media = $this->model->media()->logo()->first();

            return $this->storeMedia($media, $config, $this->request->file('logo'));
        }

        return $this;
    }
}
