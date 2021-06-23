<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = ['name', 'description'];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, UserCategory::class, 'category_id', 'user_id', 'id', 'id');
    }
}
