<?php

// app/Models/Data.php  (Laravel 8–10)
// If Laravel 7 or older, use namespace App; and put in app/Data.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = 'data';

    protected $fillable = [
        'tracking_number',
        'name',
        'address',
        'district',
        'phone_number',
        'item_code',
        'price',
        'note',
    ];
}
