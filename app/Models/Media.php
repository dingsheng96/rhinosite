<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    const TYPE_THUMBNAIL    =   'thumbnail';
    const TYPE_PROFILE      =   'profile';
    const TYPE_IMAGE        =   'image';
    const TYPE_SSM          =   'ssm';
    const TYPE_LOGO         =   'logo';
    const DEFAULT_IMAGE     =   'nopreview.png';

    protected $table = 'media';

    protected $fillable = [
        'sourceable_type', 'sourceable_id', 'type', 'original_filename',
        'filename', 'path', 'extension', 'size', 'mime', 'properties'
    ];

    // Relationships
    public function sourceable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeSsm($query)
    {
        return $query->where('type', self::TYPE_SSM);
    }

    public function scopeThumbnail($query)
    {
        return $query->where('type', self::TYPE_THUMBNAIL);
    }

    public function scopeLogo($query)
    {
        return $query->where('type', self::TYPE_LOGO);
    }

    public function scopeProfileImage($query)
    {
        return $query->where('type', self::TYPE_PROFILE);
    }

    public function scopeImage($query)
    {
        return $query->where('type', self::TYPE_IMAGE);
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

    public function getDefaultPreviewImageAttribute()
    {
        return asset('storage/' . self::DEFAULT_IMAGE);
    }
}
