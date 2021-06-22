<?php

namespace App\Support\Services;

use App\Models\Project;

class ProjectService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Project::class);
    }

    public function storeData()
    {
        $request = $this->request;

        $this->model = $this->model->firstOrCreate([
            'title'         =>  $request->get('title_en'),
            'description'   =>  $request->get('description'),
            'user_id'       =>  $request->get('user') ?? auth()->id(),
            'services'      =>  $request->get('servics'),
            'materials'     =>  $request->get('materials'),
            'currency_id'   =>  $request->get('currency'),
            'unit_price'    =>  $request->get('unit_price'),
            'unit_id'       =>  $request->get('unit'),
            'unit_value'    =>  $request->get('unit_value'),
            'on_listing'    =>  $request->has('on_listing')
        ]);

        $location = $this->saveLocation();

        return $this->getModel();
    }

    public function saveLocation()
    {
        $this->model->address()->
    }
}
