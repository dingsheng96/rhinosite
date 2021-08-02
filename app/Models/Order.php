<?php

namespace App\Models;

use App\Models\User;
use App\Helpers\Status;
use App\Models\Currency;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Support\Facades\PriceFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    const REPORT_PREFIX = 'ODR';

    protected $table = 'orders';

    protected $fillable = [
        'user_id', 'order_no', 'total_items', 'currency_id',
        'sub_total', 'discount', 'tax', 'grand_total', 'status'
    ];

    // Relationships
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'sourceable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    // Attributes
    public function setSubTotalAttribute($value)
    {
        $this->attributes['sub_total'] = PriceFacade::convertFloatToInt($value);
    }

    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = PriceFacade::convertFloatToInt($value);
    }

    public function setTaxAttribute($value)
    {
        $this->attributes['tax'] = PriceFacade::convertFloatToInt($value);
    }

    public function setGrandTotalAttribute($value)
    {
        $this->attributes['grand_total'] = PriceFacade::convertFloatToInt($value);
    }

    public function getSubTotalAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getDiscountAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getTaxAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getGrandTotalAttribute($value)
    {
        return number_format(PriceFacade::convertIntToFloat($value), 2, '.', '');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = Status::instance()->statusLabel($this->status);

        return '<span class="px-3 ' . $statuses['class'] . '">' . $statuses['text'] . '</span>';
    }

    public function getSubTotalWithCurrencyAttribute()
    {
        return $this->currency->code . ' ' . $this->sub_total;
    }

    public function getGrandTotalWithCurrencyAttribute()
    {
        return $this->currency->code . ' ' . $this->grand_total;
    }

    public function getPaidByAttribute()
    {
        return optional($this->transaction)->paymentMethod;
    }

    public function getConcatItemNameAttribute()
    {
        $items = $this->orderItems()->select('item')->get()
            ->pluck('item')->toArray();

        return Str::limit(implode(',', $items), 95, '...');
    }
}
