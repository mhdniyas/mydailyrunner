<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'unit',
        'min_stock_level',
        'current_stock',
        'avg_cost',
        'sale_price',
        'avg_bag_weight',
        'total_bags',
        'user_id',
    ];

    /**
     * Get the shop that owns the product.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the user who created the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the stock ins for the product.
     */
    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Get the stock checks for the product.
     */
    public function stockChecks()
    {
        return $this->hasMany(DailyStockCheck::class);
    }

    /**
     * Get the sale items for the product.
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Check if product is low on stock.
     */
    public function isLowStock()
    {
        return $this->current_stock > 0 && $this->current_stock <= $this->min_stock_level;
    }

    /**
     * Check if product is out of stock.
     */
    public function isOutOfStock()
    {
        return $this->current_stock <= 0;
    }

    /**
     * Get the total value of current stock.
     */
    public function getCurrentStockValue()
    {
        return $this->current_stock * $this->avg_cost;
    }
}