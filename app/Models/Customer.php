<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [

        'name',
        'phone',
        'email',
        'normalized_phone',
        'alternate_phone',
        'normalized_alternate_phone',
        'whatsapp_number',
        'normalized_whatsapp_number',
        'address',
        'city',
        'district',
        'first_order_at',
        'last_order_at',
        'total_orders',
        'delivered_orders',
        'returned_orders',
        'cancelled_orders',
        'no_answer_count',
        'fake_order_count',
        'lifetime_value',
        'trust_score',
        'risk_level',
        'is_blacklisted',
        'blacklist_reason',
        'notes',
        'user_id'

    ];
//     protected static function booted()
// {
//     static::creating(function ($customer) {

//         $phone = $customer->phone;

//         $phone = preg_replace('/[^0-9]/', '', $phone);

//         if (substr($phone, 0, 1) == '0') {
//             $phone = '94' . substr($phone, 1);
//         }

//         if (substr($phone, 0, 2) != '94') {
//             $phone = '94' . $phone;
//         }

//         $customer->normalized_phone = $phone;

//     });
// }
protected static function booted()
{
    static::saving(function ($customer) {

        // Normalize main phone
        if ($customer->phone) {
            $customer->normalized_phone = self::normalizePhone($customer->phone);
        }

        // Normalize alternate phone
        if ($customer->alternate_phone) {
            $customer->normalized_alternate_phone = self::normalizePhone($customer->alternate_phone);
        }

    });
}
public static function normalizePhone($phone)
{
    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (substr($phone, 0, 1) == '0') {
        $phone = '94' . substr($phone, 1);
    }

    if (substr($phone, 0, 2) != '94') {
        $phone = '94' . $phone;
    }

    return $phone;
}

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
  
    public function events()
    {
        return $this->hasMany(CustomerEvent::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public static function findByPhone($phone)
{
    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (substr($phone, 0, 1) === '0') {
        $phone = '94' . substr($phone, 1);
    }

    if (substr($phone, 0, 2) !== '94') {
        $phone = '94' . $phone;
    }

    return self::where('normalized_phone', $phone)->first();
}
// public function getReturnRateAttribute()
// {
//     if ($this->total_orders == 0) {
//         return 0;
//     }

//     return round(($this->returned_orders / $this->total_orders) * 100, 2);
// }
public function getReturnRateAttribute()
{
    if ($this->total_orders == 0) {
        return 0;
    }

    return round(($this->cancelled_orders / $this->total_orders) * 100, 2);
}

public function getDeliveryProbabilityAttribute()
{
    if ($this->total_orders == 0) {
        return 50;
    }

    $base = $this->trust_score;

    if ($this->no_answer_count >= 2) {
        $base -= 10;
    }

    if ($this->fake_order_count >= 1) {
        $base -= 15;
    }

    if ($this->cancelled_orders >= 2) {
        $base -= 10;
    }

    return max(0, min(100, round($base)));
}

public function getCustomerSegmentAttribute()
{
    if ($this->is_blacklisted) {
        return 'Blacklisted';
    }

    if ($this->fake_order_count >= 2) {
        return 'Fraud Risk';
    }

    if ($this->lifetime_value >= 20000 && $this->delivered_orders >= 5) {
        return 'Premium Customer';
    }

    if ($this->delivered_orders >= 3 && $this->return_rate < 20) {
        return 'Good Customer';
    }

    if ($this->return_rate > 40) {
        return 'High Risk';
    }

    if ($this->total_orders <= 2) {
        return 'New Customer';
    }

    return 'Regular Customer';
}
public function user()
{
    return $this->belongsTo(User::class);
}

public function updateStats()
{
    $this->total_orders = $this->orders()->count();

    $this->delivered_orders = $this->orders()
        ->where('delivery_status', 'delivered')
        ->count();

    $this->returned_orders = $this->orders()
        ->where('delivery_status', 'cancelled')
        ->count();

    $this->fake_order_count = $this->orders()
        ->where('verification_status', 'fake')
        ->count();

    $this->no_answer_count = $this->orders()
        ->where('verification_status', 'no_answer')
        ->count();

    $this->lifetime_value = $this->orders()
        ->where('delivery_status', 'delivered')
        ->sum('total_amount');
    // $this->lifetime_value = $this->orders()
    // ->whereIn('delivery_status', ['delivered'])
    // ->sum('total_amount');

    // 🔥 RISK LOGIC
//     $returnRate = $this->total_orders > 0 
//     ? $this->returned_orders / $this->total_orders 
//     : 0;
    
// if ($this->fake_order_count >= 2) {
//     $this->risk_level = 'very_high';
// } elseif ($returnRate >= 0.5) {
//     $this->risk_level = 'high';
// } elseif ($returnRate >= 0.3) {
//     $this->risk_level = 'medium';
// } elseif ($this->total_orders >= 3 && $returnRate == 0) {
//     $this->risk_level = 'low';
// } else {
//     $this->risk_level = 'new';
// }
$returnRate = $this->total_orders > 0
        ? $this->returned_orders / $this->total_orders
        : 0;

    // 🔥 RISK LOGIC
    if ($this->fake_order_count >= 2) {
        $this->risk_level = 'very_high';

    } elseif ($this->no_answer_count >= 3) {
        $this->risk_level = 'very_high';

    } elseif ($returnRate >= 0.5) {
        $this->risk_level = 'high';

    } elseif ($returnRate >= 0.3) {
        $this->risk_level = 'medium';

    } elseif ($this->total_orders >= 3 && $returnRate == 0 && $this->no_answer_count == 0) {
        $this->risk_level = 'low';

    } else {
        $this->risk_level = 'new';
    }

    //trust score
//     $score = 100;

// $score -= ($this->returned_orders * 10);
// $score -= ($this->fake_order_count * 25);
// $score -= ($this->no_answer_count * 5);
// $score += ($this->delivered_orders * 5);

// $this->trust_score = max(0, min(100, $score));

$score = 0;

if ($this->total_orders > 0) {
    $deliveryRate = $this->delivered_orders / $this->total_orders;
    $returnRate = $this->returned_orders / $this->total_orders;

    $score = ($deliveryRate * 100)
           - ($returnRate * 50)
           - ($this->fake_order_count * 20)
           - ($this->no_answer_count * 5);
}

$this->trust_score = max(0, min(100, round($score)));
    $this->save();
}

}