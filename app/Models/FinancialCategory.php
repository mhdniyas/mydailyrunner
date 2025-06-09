<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialCategory extends Model
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
        'type',
        'description',
    ];

    /**
     * Get the shop that owns the category.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the financial entries for the category.
     */
    public function entries()
    {
        return $this->hasMany(FinancialEntry::class);
    }

    /**
     * Check if the category is for income.
     */
    public function isIncome()
    {
        return $this->type === 'income';
    }

    /**
     * Check if the category is for expense.
     */
    public function isExpense()
    {
        return $this->type === 'expense';
    }
}