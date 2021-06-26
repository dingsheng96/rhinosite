<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    use SoftDeletes;

    const TYPE_SUBSCRIPTION = 'Subscription';

    protected $table = 'product_types';

    protected $fillable = ['name', 'description'];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'product_type_id', 'id');
    }
}
