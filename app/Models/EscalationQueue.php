<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EscalationQueue extends Model
{
    protected $fillable = [
        'order_id',
        'reason_code',
        'priority',
        'queued_at',
        'resolved_at',
        'assigned_to',
        'status',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
