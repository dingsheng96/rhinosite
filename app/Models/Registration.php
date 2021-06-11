<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    protected $table = 'registrations';

    protected $fillable = [
        'name', 'email', 'mobile_no', 'tel_no', 'reg_no',
        'status', 'validate_by', 'validate_at'
    ];

    protected $casts = [
        'validate_at' => 'datetime'
    ];
}
