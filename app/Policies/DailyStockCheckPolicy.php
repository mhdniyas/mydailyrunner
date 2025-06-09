<?php

namespace App\Policies;

use App\Models\DailyStockCheck;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DailyStockCheckPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any user with access to the shop can view stock checks
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DailyStockCheck $dailyStockCheck): bool
    {
        // Check if user has access to the shop that owns this stock check
        return $user->shops->contains($dailyStockCheck->shop_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only owner, manager, and stock roles can create stock checks
        return $user->hasAnyRole(['owner', 'manager', 'stock']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DailyStockCheck $dailyStockCheck): bool
    {
        // Stock checks should not be updated after creation
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailyStockCheck $dailyStockCheck): bool
    {
        // Only owner and manager can delete stock checks
        if (!$user->shops->contains($dailyStockCheck->shop_id)) {
            return false;
        }
        
        return $user->hasAnyRole(['owner', 'manager'], $dailyStockCheck->shop_id);
    }
}