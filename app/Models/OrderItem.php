<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [

        'order_id',
        'product_category_id',
        'product_name',
        'sku',
        'quantity',
        'unit_price',
        'total_price'

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id');
    }
}