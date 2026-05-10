<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    protected $fillable = [
        'user_id',
        'searched_phone',
        'normalized_phone',
        'result_count',
        'found',
        'delivery_probability',
        'risk_level',
        'search_type',
    ];
}