<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strap extends Model
{
    protected $fillable = ['title','item_code', 'quantity', 'image_path'];
}
