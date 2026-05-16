<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'gender','item_code','title','cost','image_path','is_cut',
        'business_unit_id','sku','name','selling_price','product_cost','expected_courier_cost','weight','is_active','notes',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'is_cut' => 'boolean',
        'selling_price' => 'decimal:2',
        'product_cost' => 'decimal:2',
        'expected_courier_cost' => 'decimal:2',
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

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
