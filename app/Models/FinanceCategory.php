<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceCategory extends Model
{
    protected $fillable = [
        'name',
        'type',
        'scope',
        'icon',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function shopFinanceEntries(): HasMany
    {
        return $this->hasMany(ShopFinanceEntry::class, 'category_id');
    }

    public function personalFinanceEntries(): HasMany
    {
        return $this->hasMany(PersonalFinanceEntry::class, 'category_id');
    }
}
