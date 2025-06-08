<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockEntry extends Model
{
    protected $fillable = [
        'product_id',
        'entry_date',
        'quantity',
        'type',
        'entry_type',
        'remarks',
        'user_id'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'quantity' => 'decimal:2'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
