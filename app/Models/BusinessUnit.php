<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function moneyRecords(): HasMany
    {
        return $this->hasMany(MoneyRecord::class);
    }

    public function codAssumptions(): HasMany
    {
        return $this->hasMany(CODAssumption::class);
    }

    public function dailyCodOperations(): HasMany
    {
        return $this->hasMany(DailyCODOperation::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
