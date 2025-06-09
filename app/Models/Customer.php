<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
        'phone',
        'address',
        'is_walk_in',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_walk_in' => 'boolean',
    ];

    /**
     * Get the shop that owns the customer.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the sales for the customer.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the total amount due from this customer.
     */
    public function getTotalDue()
    {
        return $this->sales()
            ->where('status', '!=', 'paid')
            ->sum('due_amount');
    }

    /**
     * Get the count of pending sales for this customer.
     */
    public function getPendingSalesCount()
    {
        return $this->sales()
            ->where('status', '!=', 'paid')
            ->count();
    }
}