<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [

        'customer_id',
        'address_line',
        'city',
        'district',
        'normalized_address',
        'address_hash',
        'is_risky',
        'risk_notes'

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}