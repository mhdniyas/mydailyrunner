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
        'category_id',
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
     * Get the category of the product.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
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

    /**
     * Get the batches for the product.
     */
    public function batches()
    {
        return $this->hasMany(StockBatch::class);
    }
    
    /**
     * Get latest batch for the product.
     */
    public function getLatestBatch()
    {
        return $this->batches()->latest('batch_date')->first();
    }
    
    /**
     * Get latest bag average from batches.
     */
    public function getLatestBagAverage()
    {
        $latestBatch = $this->getLatestBatch();
        return $latestBatch ? $latestBatch->bag_average : $this->avg_bag_weight;
    }
    
    /**
     * Calculate weighted average bag weight from active batches.
     */
    public function calculateWeightedBagAverage()
    {
        $batches = $this->batches()->latest('batch_date')->take(5)->get();
        
        if ($batches->isEmpty()) {
            return $this->avg_bag_weight;
        }
        
        $totalBags = $batches->sum('bags');
        
        if ($totalBags == 0) {
            return $this->avg_bag_weight;
        }
        
        $weightedSum = $batches->sum(function($batch) {
            return $batch->bags * $batch->bag_average;
        });
        
        return $weightedSum / $totalBags;
    }
}