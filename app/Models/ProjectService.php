<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectService extends Pivot
{
    protected $table = 'project_service';

    protected $fillable = ['project_id', 'service_id'];
}
