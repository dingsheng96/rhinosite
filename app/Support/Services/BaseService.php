<?php

namespace App\Support\Services;

class BaseService
{
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function getModel()
    {
        $this->model->refresh();

        return $this->model;
    }
}
