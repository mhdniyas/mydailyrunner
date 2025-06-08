<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockCheck extends Model
{
    protected $fillable = [
        'product_id',
        'check_date',
        'physical_quantity',
        'system_quantity',
        'remarks',
        'user_id'
    ];

    protected $casts = [
        'check_date' => 'date',
        'physical_quantity' => 'decimal:2',
        'system_quantity' => 'decimal:2',
        'difference' => 'decimal:2'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Check if there's a significant discrepancy
    public function hasDiscrepancy(float $threshold = 1.0): bool
    {
        return abs($this->difference) > $threshold;
    }
}
