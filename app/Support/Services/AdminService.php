<?php

namespace App\Support\Services;

use App\Models\Role;
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
        $this->model->password  =   $this->request->get('password');
        $this->model->type      =   User::TYPE_ADMIN;

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->assignRole();

        return $this;
    }

    public function assignRole()
    {
        $role = Role::where('id', $this->request->get('role'))->first();

        $this->model->syncRoles([$role->name]);

        return $this;
    }
}
