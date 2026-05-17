<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCODOperation extends Model
{
    use HasFactory;

    protected $table = 'daily_cod_operations';

    protected $fillable = [
        'operation_date',
        'order_code',
        'business_unit_id',
        'product_id',
        'quantity',
        'selling_price',
        'product_cost',
        'courier_cost',
        'expected_return_percentage',
        'expected_profit',
        'status',
        'actual_profit',
        'delivered_quantity',
        'returned_quantity',
        'notes',
    ];

    protected $casts = [
        'operation_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if (empty($model->status)) {
                $model->status = 'queued';
            }

            $model->expected_profit = $model->calculateExpectedProfit();
            $model->actual_profit = $model->calculateActualProfit();
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

    public function calculateActualProfit(): ?float
    {
        if (($this->delivered_quantity ?? 0) <= 0 && ($this->returned_quantity ?? 0) <= 0) {
            return null;
        }

        $delivered = (int) ($this->delivered_quantity ?? 0);
        $returned = (int) ($this->returned_quantity ?? 0);

        $revenue = $delivered * $this->selling_price;
        $productCost = ($delivered + $returned) * $this->product_cost;
        $courierCost = ($delivered + $returned) * $this->courier_cost;
        $returnLoss = $returned * $this->courier_cost;

        return round($revenue - $productCost - $courierCost - $returnLoss, 2);
    }
}
