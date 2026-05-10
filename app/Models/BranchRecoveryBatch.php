<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchRecoveryBatch extends Model
{
    protected $fillable = [
        'courier_branch_id',
        'client_user_id',
        'batch_date',
        'status',
        'sent_at',
        'acknowledged_at',
        'acted_at',
        'notes',
    ];

    public function courierBranch()
    {
        return $this->belongsTo(CourierBranch::class);
    }

    public function items()
    {
        return $this->hasMany(BranchRecoveryBatchItem::class);
    }
}
