<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    const TYPE_ADS = 'Ads';

    protected $table = 'product_categories';

    protected $fillable = ['name', 'description'];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id', 'id');
    }
}
