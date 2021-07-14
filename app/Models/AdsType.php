<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsType extends Model
{
    use SoftDeletes;

    protected $table = 'ads_types';

    protected $fillable = ['name', 'description', 'color'];
}
