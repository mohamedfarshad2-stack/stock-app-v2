<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaStat extends Model
{
    protected $fillable = [

        'district',
        'city',
        'total_orders',
        'delivered_orders',
        'returned_orders',
        'return_rate',
        'risk_level'

    ];
}