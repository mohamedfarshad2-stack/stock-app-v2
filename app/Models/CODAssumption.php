<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CODAssumption extends Model
{
    use HasFactory;

    protected $table = 'cod_assumptions';

    protected $fillable = [
        'business_unit_id',
        'expected_return_percentage',
        'expected_return_courier_cost',
        'expected_recovery_percentage',
        'expected_recovery_cost',
        'default_cod_margin_percentage',
        'notes',
    ];

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
