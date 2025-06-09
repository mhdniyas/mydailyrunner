<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStockCheck extends Model
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
        'check_type',
        'system_stock',
        'physical_stock',
        'discrepancy',
        'discrepancy_percent',
        'notes',
        'user_id',
    ];

    /**
     * Get the shop that owns the stock check.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the product for the stock check.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who performed the stock check.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if there is a discrepancy.
     */
    public function hasDiscrepancy()
    {
        return $this->discrepancy != 0;
    }

    /**
     * Check if the discrepancy is significant (more than 5%).
     */
    public function hasSignificantDiscrepancy()
    {
        return abs($this->discrepancy_percent) > 5;
    }
}