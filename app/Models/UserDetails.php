<?php

namespace App\Models;

use App\Helpers\Status;
use App\Models\Settings\Role\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetails extends Model
{
    use SoftDeletes;

    const STATUS_PENDING    =   'pending';
    const STATUS_REJECTED   =   'rejected';
    const STATUS_APPROVED   =   'approved';

    protected $table = 'user_details';

    protected $fillable = [
        'user_id', 'years_of_experience', 'website', 'facebook', 'pic_name',
        'pic_phone', 'pic_email', 'validated_by', 'status', 'validated_at'
    ];

    protected $casts = [
        'validated_at' => 'datetime'
    ];

    // Relationships
    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by', 'id')
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_SUPER_ADMIN);
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_MERCHANT);
            });
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'sourceable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'sourceable');
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $label = Status::instance()->statusLabel($this->status);

        return '<h5><span class="' . $label['class'] . '">' . $label['text'] . '</span></h5>';
    }
}
