<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierBranch extends Model
{
    protected $fillable = [
        'courier_name',
        'branch_name',
        'district',
        'contact_phone',
        'contact_whatsapp',
        'preferred_mode',
        'active',
    ];

    public function recoveryBatches()
    {
        return $this->hasMany(BranchRecoveryBatch::class);
    }
}
