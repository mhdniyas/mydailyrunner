<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvgConsumptionCache extends Model
{
    protected $fillable = [
        'product_id',
        'avg_per_day',
        'total_consumed',
        'days_tracked',
        'last_calculated'
    ];

    protected $casts = [
        'avg_per_day' => 'decimal:4',
        'total_consumed' => 'decimal:2',
        'last_calculated' => 'date'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Calculate days until stock runs out based on current consumption
    public function getDaysUntilStockOut(): ?int
    {
        if ($this->avg_per_day <= 0) {
            return null;
        }

        $currentStock = $this->product->getCurrentSystemStock();
        return $currentStock > 0 ? ceil($currentStock / $this->avg_per_day) : 0;
    }
}
