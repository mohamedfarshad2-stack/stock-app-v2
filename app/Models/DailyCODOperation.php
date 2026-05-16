<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCODOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_date','business_unit_id','product_id','quantity','selling_price','product_cost','courier_cost',
        'expected_return_percentage','expected_profit','delivered_quantity','returned_quantity','notes',
    ];

    protected $casts = [
        'operation_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $model->expected_profit = $model->calculateExpectedProfit();
        });
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateExpectedProfit(): float
    {
        $grossRevenue = $this->quantity * $this->selling_price;
        $productTotalCost = $this->quantity * $this->product_cost;
        $courierTotalCost = $this->quantity * $this->courier_cost;
        $expectedReturnLoss = ($this->quantity * $this->expected_return_percentage / 100) * $this->courier_cost;

        return round($grossRevenue - $productTotalCost - $courierTotalCost - $expectedReturnLoss, 2);
    }
}
