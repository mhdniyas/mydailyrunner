<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'ration_card_number',
        'card_type',
        'notes',
        'is_walk_in',
        'shop_id',
        'user_id',
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
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the sales for the customer.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the user who created the customer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the card type display name.
     */
    public function getCardTypeDisplayAttribute(): string
    {
        return match($this->card_type) {
            'AAY' => 'AAY (Yellow)',
            'PHH' => 'PHH (Pink)',
            'NPS' => 'NPS (Blue)',
            'NPI' => 'NPI (Light Blue)',
            'NPNS' => 'NPNS (White)',
            default => 'Not Specified'
        };
    }
}