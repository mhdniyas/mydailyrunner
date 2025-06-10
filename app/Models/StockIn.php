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
        'calculation_method',
        'manual_bag_weight',
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

    /**
     * Get the actual bag weight based on calculation method.
     */
    public function getActualBagWeight()
    {
        return match($this->calculation_method) {
            'manual' => $this->manual_bag_weight,
            'formula_direct' => $this->quantity / $this->bags,
            'formula_minus_half' => ($this->quantity / $this->bags) - 0.5,
            default => $this->avg_bag_weight
        };
    }

    /**
     * Get the calculation method display name.
     */
    public function getCalculationMethodDisplay()
    {
        return match($this->calculation_method) {
            'manual' => 'Manual Override',
            'formula_direct' => 'Quantity ÷ Bags',
            'formula_minus_half' => '(Quantity ÷ Bags) - 0.5',
            default => 'Unknown'
        };
    }
}