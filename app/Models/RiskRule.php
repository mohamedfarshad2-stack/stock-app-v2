<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskRule extends Model
{
    protected $fillable = [

        'rule_key',
        'rule_name',
        'score_effect',
        'is_active',
        'description'

    ];
}