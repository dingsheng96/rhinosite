<?php

namespace App\Support\Services;

use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AccountVerified;

class UserDetailService extends BaseService
{
    public function __construct()
    {
        parent::__construct(UserDetail::class);
    }

    public function verify()
    {
        $status = $this->request->get('status');

        if ($status != UserDetail::STATUS_PENDING) {
            $this->model->validated_by  =   Auth::id();
            $this->model->status        =   $status;
            $this->model->validated_at  =   now();
            $this->model->save();

            // send email notification
            $this->model->user->notify(new AccountVerified($this->model));
        }

        return $this->getModel();
    }
}
