<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}