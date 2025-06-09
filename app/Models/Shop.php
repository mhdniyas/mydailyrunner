<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'owner_id',
    ];

    /**
     * Get the owner of the shop.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users associated with the shop.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'shop_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the shop users records.
     */
    public function shopUsers()
    {
        return $this->hasMany(ShopUser::class);
    }

    /**
     * Get the products for the shop.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the stock ins for the shop.
     */
    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Get the daily stock checks for the shop.
     */
    public function dailyStockChecks()
    {
        return $this->hasMany(DailyStockCheck::class);
    }

    /**
     * Get the customers for the shop.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the sales for the shop.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the financial categories for the shop.
     */
    public function financialCategories()
    {
        return $this->hasMany(FinancialCategory::class);
    }

    /**
     * Get the financial entries for the shop.
     */
    public function financialEntries()
    {
        return $this->hasMany(FinancialEntry::class);
    }
}