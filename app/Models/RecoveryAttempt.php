<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecoveryAttempt extends Model
{
    protected $fillable = [
        'order_id',
        'actor_type',
        'channel',
        'outcome_code',
        'outcome_note',
        'attempted_at',
        'created_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
