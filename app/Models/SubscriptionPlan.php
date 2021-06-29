<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use SoftDeletes;

    protected $table = 'subscription_plans';

    protected $fillable = ['name'];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Product::class, SubscriptionPlanProduct::class, 'subscription_plan_id', 'product_id', 'id', 'id')
            ->withPivot(['quantity']);
    }
}
