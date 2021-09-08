<?php

namespace App\Support\Services;

use App\Models\Role;
use App\Models\User;

class RoleService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Role::class);
    }

    public function store()
    {
        $this->model->name = $this->request->get('name');
        $this->model->description = $this->request->get('description');
        $this->model->guard_name = config('auth.defaults.guard');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->assignPermissions();

        return $this;
    }

    public function assignPermissions()
    {
        $this->model->syncPermissions($this->request->get('permissions'));

        return $this;
    }
}
