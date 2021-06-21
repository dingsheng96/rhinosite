<?php

namespace App\Support\Services;

use App\Models\User;
use App\Models\Registration;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;
use App\Models\Settings\Role\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegistrationService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Registration::class);
    }

    private function createNewMerchant(): User
    {
        $model = $this->model;

        $user = $model->merchant()->create([
            'name'      =>  $model->name,
            'email'     =>  $model->email,
            'mobile_no' =>  $model->mobile_no,
            'tel_no'    =>  $model->tel_no,
            'reg_no'    =>  $model->reg_no,
            'status'    =>  User::STATUS_ACTIVE,
        ]);

        $user->assignRole(Role::ROLE_MERCHANT);

        return $user;
    }

    public function validateRegistration(string $status)
    {
        if ($status != Registration::STATUS_PENDING) {
            $this->model->validate_by   =   Auth::id();
            $this->model->validated_at  =   now();
            $this->model->save();

            if ($status == Registration::STATUS_APPROVED) {
                $user = $this->createNewMerchant();
            }

            $this->sendNotificationEmail($status, $user ?? $this->model);
        }

        return $this->getModel();
    }

    private function sendNotificationEmail(string $status, Registration $target): void
    {
        if ($status == Registration::STATUS_REJECTED) {
            Mail::send(new RegistrationRejected($target)); // registration
        }

        if ($status == Registration::STATUS_APPROVED) {
            Mail::send(new RegistrationApproved($target)); // $new user
        }
    }
}
