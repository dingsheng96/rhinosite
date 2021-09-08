<?php

namespace App\Support\Services;

use App\Models\User;

class AdminService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function store()
    {
        $this->model->name      =   $this->request->get('name');
        $this->model->email     =   $this->request->get('email');
        $this->model->password  =   $this->request->get('new_password');

        if ($this->model->isDirty()) {
            $this->model->save();
        }


        return $this;
    }
}
