<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [

        'customer_id',
        'channel_id',
        'external_order_no',
        'source',
        'channel_reference',
        'order_date',
        'subtotal',
        'discount',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'courier_name',
        'address',
        'city',
        'district',
        'verification_status',
        'delivery_status',
        'return_reason',
        'risk_score',
        'risk_level',
        'recommended_action',
        'verified_by',
        'verified_at',
        'shipped_at',
        'delivered_at',
        'returned_at',
        'user_id'

    ];
// protected static function booted()
// {
//     static::creating(function ($order) {
//         $order->verification_status = 'pending';
//     });
// }
// protected static function booted()
// {
//     static::creating(function ($order) {
//         $order->user_id = auth()->id();
//     });
// }
protected static function booted()
{
    // 🔹 When creating order → set user_id
    static::creating(function ($order) {
        $order->user_id = auth()->id();
    });

    // 🔹 When order is created/updated → update customer stats
    static::saved(function ($order) {
        if ($order->customer) {
            $order->customer->updateStats();
        }
    });
}
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function verifications()
    {
        return $this->hasMany(OrderVerification::class);
    }

    public function events()
    {
        return $this->hasMany(CustomerEvent::class);
    }
}