<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockBatch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'product_id',
        'stock_in_id',
        'user_id',
        'batch_date',
        'quantity',
        'bags',
        'bag_average',
        'cost',
        'supplier',
        'expiry_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'batch_date' => 'date',
        'expiry_date' => 'date',
        'quantity' => 'decimal:2',
        'bag_average' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the shop that owns the batch.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the product for the batch.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the stock in record associated with this batch.
     */
    public function stockIn(): BelongsTo
    {
        return $this->belongsTo(StockIn::class);
    }

    /**
     * Get the user who created the batch.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the total cost of this batch.
     */
    public function getTotalCost()
    {
        return $this->cost;
    }

    /**
     * Check if batch is expiring soon (within 7 days).
     */
    public function isExpiringSoon()
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return now()->diffInDays($this->expiry_date, false) <= 7;
    }

    /**
     * Check if batch has expired.
     */
    public function hasExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return now()->greaterThan($this->expiry_date);
    }
}
