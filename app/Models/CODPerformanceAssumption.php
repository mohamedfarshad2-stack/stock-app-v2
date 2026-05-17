<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CODPerformanceAssumption extends Model
{
    use HasFactory;

    protected $table = 'cod_assumptions';

    protected $fillable = [
        'business_unit_id',
        'delivery_charge',
        'return_charge',
        'expected_return_percentage',
        'notes',
    ];

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
