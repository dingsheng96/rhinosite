<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    const CODE_EN = 'en';
    const CODE_CN = 'cn';

    protected $table = 'languages';

    protected $fillable = [
        'name', 'code'
    ];

    // Relationships
    public function translations()
    {
        return $this->hasMany(Translation::class, 'language_id', 'id');
    }
}
