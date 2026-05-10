<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
protected $fillable = ['gender','item_code','title','cost','image_path','is_cut'];
protected $casts = ['cost' => 'decimal:2', 'is_cut' => 'boolean'];

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    // Helper: returns array [size => quantity]
    public function sizeMap(): array
    {
        return $this->sizes->pluck('quantity','size')->all();
    }

    public function strap()
    {
        return $this->hasOne('App\Models\Strap','item_code','item_code');

    }
}

