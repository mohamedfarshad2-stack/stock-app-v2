<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoneyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_unit_id',
        'finance_category_id',
        'user_id',
        'record_date',
        'type',
        'amount',
        'payment_method',
        'reference_no',
        'description',
        'status',
        'attachment_path',
    ];

    protected $casts = [
        'record_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function financeCategory(): BelongsTo
    {
        return $this->belongsTo(FinanceCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
