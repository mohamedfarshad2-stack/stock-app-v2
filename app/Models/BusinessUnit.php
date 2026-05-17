<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeCodCompatible(Builder $query): Builder
    {
        return $query->whereIn('type', ['cod', 'hybrid']);
    }

    public function moneyRecords(): HasMany
    {
        return $this->hasMany(MoneyRecord::class);
    }

    public function codPerformanceAssumptions(): HasMany
    {
        return $this->hasMany(CODPerformanceAssumption::class);
    }

    /**
     * Backward compatibility alias.
     */
    public function codAssumptions(): HasMany
    {
        return $this->hasMany(CODPerformanceAssumption::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}