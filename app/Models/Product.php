<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'category',
        'threshold',
        'description',
        'is_active'
    ];

    protected $casts = [
        'threshold' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }

    public function stockChecks(): HasMany
    {
        return $this->hasMany(StockCheck::class);
    }

    public function avgConsumptionCache()
    {
        return $this->hasOne(AvgConsumptionCache::class);
    }

    // Calculate current system stock
    public function getCurrentSystemStock(): float
    {
        return $this->stockEntries()
            ->selectRaw('SUM(CASE WHEN entry_type = "in" THEN quantity ELSE -quantity END) as total')
            ->value('total') ?? 0;
    }

    // Get latest physical stock check
    public function getLatestPhysicalStock(): ?float
    {
        $latestCheck = $this->stockChecks()->latest('check_date')->first();
        return $latestCheck ? $latestCheck->physical_quantity : null;
    }

    // Check if stock is below threshold
    public function isBelowThreshold(): bool
    {
        return $this->getCurrentSystemStock() < $this->threshold;
    }
}
