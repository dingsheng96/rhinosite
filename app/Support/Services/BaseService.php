<?php

namespace App\Support\Services;

use Illuminate\Http\Request;

class BaseService
{
    public $model, $request;

    public function __construct($model)
    {
        $this->model = new $model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getModel()
    {
        $this->model->refresh();

        return $this->model;
    }
}
