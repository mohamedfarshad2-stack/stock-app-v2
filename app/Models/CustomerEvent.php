<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEvent extends Model
{
    protected $fillable = [

        'customer_id',
        'order_id',
        'event_type',
        'event_value',
        'score_impact',
        'notes',
        'created_by'

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}