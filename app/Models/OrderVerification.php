<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderVerification extends Model
{
    protected $fillable = [

        'order_id',
        'verification_method',
        'verification_result',
        'attempt_no',
        'remarks',
        'verified_by',
        'verified_at'

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}