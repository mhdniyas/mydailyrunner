<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopFinanceEntry extends Model
{
    protected $fillable = [
        'category_id',
        'entry_date',
        'amount',
        'type',
        'description',
        'remarks',
        'reference_number',
        'user_id'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceCategory::class, 'category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
