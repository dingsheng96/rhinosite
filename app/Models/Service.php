<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $table = 'services';

    protected $fillable = ['name', 'description'];

    // Relationships
    public function userDetails()
    {
        return $this->hasMany(UserDetail::class, 'service_id', 'id');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, UserDetail::class, 'service_id', 'id', 'id', 'user_id');
    }

    // Attributes
    public function getNameWithProjectCountAttribute()
    {
        $users = $this->users;
        $projects_count = 0;

        foreach ($users as $user) {
            $projects_count += $user->projects->count();
        }

        if ($projects_count > 0) {

            return $this->name . ' (' . $projects_count . ')';
        }

        return $this->name;
    }
}
