<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    const TYPE_ADS = 'Ads';
    const TYPE_SUBSCRIPTION = 'Subscription';

    protected $table = 'product_categories';

    protected $fillable = ['name', 'description', 'enable_slot'];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id', 'id');
    }
}
