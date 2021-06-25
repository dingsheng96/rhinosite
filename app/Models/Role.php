<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use SoftDeletes;

    const ROLE_SUPER_ADMIN = 'Super Admin';
    const ROLE_MERCHANT = 'Merchant';
    const ROLE_MEMBER = 'Member';
}
