<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'type',
        'financial_category_id',
        'amount',
        'entry_date',
        'description',
        'reference',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'entry_date' => 'date',
    ];

    /**
     * Get the shop that owns the entry.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the category for the entry.
     */
    public function category()
    {
        return $this->belongsTo(FinancialCategory::class, 'financial_category_id');
    }

    /**
     * Get the user who created the entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the entry is income.
     */
    public function isIncome()
    {
        return $this->type === 'income';
    }

    /**
     * Check if the entry is expense.
     */
    public function isExpense()
    {
        return $this->type === 'expense';
    }

    /**
     * Get the entry type as a formatted string.
     */
    public function getTypeBadge()
    {
        if ($this->isIncome()) {
            return '<span class="badge bg-success">Income</span>';
        } else {
            return '<span class="badge bg-danger">Expense</span>';
        }
    }
}