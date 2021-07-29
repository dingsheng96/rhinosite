<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'name', 'description'
    ];

    // Relationships
    public function projects()
    {
        return $this->hasMany(Project::class, 'unit_id', 'id');
    }

    // Attributes
    public function getFullDisplayAttribute()
    {
        return $this->name . ' (' . $this->display . ')';
    }
}
