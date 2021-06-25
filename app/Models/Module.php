<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $table = 'modules';

    protected $fillable = ['name', 'description'];

    // Relationships
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module_id', 'id');
    }
}
