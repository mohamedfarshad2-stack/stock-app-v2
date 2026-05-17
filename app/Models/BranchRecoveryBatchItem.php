<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchRecoveryBatchItem extends Model
{
    protected $fillable = [
        'branch_recovery_batch_id',
        'order_id',
        'action_required',
        'item_status',
        'notes',
    ];

    public function batch()
    {
        return $this->belongsTo(BranchRecoveryBatch::class, 'branch_recovery_batch_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
