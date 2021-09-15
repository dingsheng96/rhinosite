<?php

namespace App\Support\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\BaseService;

class RoleService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Role::class);
    }

    public function store()
    {
        $this->model->name          = $this->request->get('name');
        $this->model->description   = $this->request->get('description');
        $this->model->guard_name    = $this->getGuardFromUserType(Auth::user()->type);

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

    private function getGuardFromUserType(string $user_type)
    {
        $guards =  [
            'admin' => 'admin',
            'merchant' => 'merchant'
        ];

        return $guards[$user_type] ?? config('auth.defaults.guard');
    }
}
