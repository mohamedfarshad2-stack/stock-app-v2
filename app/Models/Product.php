<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'gender',
        'item_code',
        'title',
        'cost',
        'image_path',
        'is_cut',
        'business_unit_id',
        'sku',
        'name',
        'selling_price',
        'product_cost',
        'strap_maker_cost',
        'stitching_worker_cost',
        'finishing_worker_cost',
        'expected_courier_cost',
        'packaging_cost',
        'advertisement_allocation',
        'operational_overhead',
        'return_loss_estimate',
        'weight',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'is_cut' => 'boolean',
        'selling_price' => 'decimal:2',
        'product_cost' => 'decimal:2',
        'strap_maker_cost' => 'decimal:2',
        'stitching_worker_cost' => 'decimal:2',
        'finishing_worker_cost' => 'decimal:2',
        'expected_courier_cost' => 'decimal:2',
        'packaging_cost' => 'decimal:2',
        'advertisement_allocation' => 'decimal:2',
        'operational_overhead' => 'decimal:2',
        'return_loss_estimate' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function sizeMap(): array
    {
        return $this->sizes->pluck('quantity', 'size')->all();
    }

    public function strap()
    {
        return $this->hasOne(Strap::class, 'item_code', 'item_code');
    }

    public function expectedParcelProfitability(): float
    {
        return round(
            (float) $this->selling_price
            - (float) $this->product_cost
            - (float) ($this->strap_maker_cost ?? 0)
            - (float) ($this->stitching_worker_cost ?? 0)
            - (float) ($this->finishing_worker_cost ?? 0)
            - (float) ($this->expected_courier_cost ?? 0)
            - (float) ($this->packaging_cost ?? 0)
            - (float) ($this->advertisement_allocation ?? 0)
            - (float) ($this->operational_overhead ?? 0)
            - (float) ($this->return_loss_estimate ?? 0),
            2
        );
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}