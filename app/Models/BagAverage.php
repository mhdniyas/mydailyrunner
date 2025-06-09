<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BagAverage extends Model
{
    protected $fillable = [
        'shop_id',
        'product_id',
        'quantity',
        'bags',
        'calculated_avg',
        'stock_in_id',
        'date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'calculated_avg' => 'decimal:2',
        'date' => 'date',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockIn(): BelongsTo
    {
        return $this->belongsTo(StockIn::class);
    }

    protected static function booted()
    {
        static::creating(function ($bagAverage) {
            if (empty($bagAverage->calculated_avg)) {
                $bagAverage->calculated_avg = $bagAverage->quantity / $bagAverage->bags;
            }
        });
    }
}
