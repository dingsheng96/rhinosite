<?php

namespace App\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use SoftDeletes;

    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_READ   = 'read';
    const ACTION_DELETE = 'delete';

    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
