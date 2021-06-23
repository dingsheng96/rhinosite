<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserCategory extends Pivot
{
    protected $table = 'user_category';
}
