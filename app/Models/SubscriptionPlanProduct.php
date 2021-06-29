<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubscriptionPlanProduct extends Pivot
{
    protected $table = 'subscription_plan_product';

    protected $fillable = [
        'subscription_plan_id', 'product_id', 'quantity'
    ];
}
