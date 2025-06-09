<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
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
        'quantity',
        'bags',
        'cost',
        'avg_bag_weight',
        'notes',
        'user_id',
    ];

    /**
     * Get the shop that owns the stock in.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the product for the stock in.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created the stock in.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the total cost of this stock in.
     */
    public function getTotalCost()
    {
        return $this->cost;
    }
}