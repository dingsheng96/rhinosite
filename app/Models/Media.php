<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    const TYPE_THUMBNAIL = 'thumbnail';
    const TYPE_PROFILE = 'profile';

    protected $table = 'media';

    protected $fillable = [];

    // Relationships
    public function sourceable()
    {
        return $this->morphTo();
    }

    // Attributes
    public function getFilePathAttribute()
    {
        return rtrim($this->path, '/') . '/' . $this->filename;
    }

    public function getFullFilePathAttribute()
    {
        return asset('storage/' . ltrim($this->file_path, '/'));
    }

    public function getSizeInBytesAttribute()
    {
        return number_format($this->size, 0, '', '');
    }

    public function getTypeInTextAttribute()
    {
        return ucwords(str_replace("-", " ", $this->type));
    }
}
