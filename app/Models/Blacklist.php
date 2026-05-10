<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $fillable = [

        'customer_id',
        'normalized_phone',
        'address_hash',
        'reason',
        'severity',
        'is_active',
        'created_by'

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}