<?php

namespace App\Models;

use App\Helpers\Status;
use App\Models\Settings\Role\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_REJECTED = 'rejected';
    const STATUS_APPROVED = 'approved';

    protected $table = 'registrations';

    protected $fillable = [
        'name', 'email', 'mobile_no', 'tel_no', 'reg_no',
        'status', 'validate_by', 'validate_at'
    ];

    protected $casts = [
        'validate_at' => 'datetime'
    ];

    // Relationships
    public function validateBy()
    {
        return $this->belongsTo(User::class, 'validate_by', 'id')
            ->whereHas('roles', function ($query) {
                $query->where('name', Role::ROLE_SUPER_ADMIN);
            });
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'registration_id', 'id')
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
