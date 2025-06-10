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
        // Admins can view any stock check
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has access to the shop that owns this stock check
        return $user->shops()->where('shops.id', $dailyStockCheck->shop_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins can create stock checks
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner, manager, and stock roles can create stock checks
        return $user->hasAnyRole(['owner', 'manager', 'stock']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DailyStockCheck $dailyStockCheck): bool
    {
        // Admins can update stock checks if needed
        if ($user->isAdmin()) {
            return true;
        }
        
        // Stock checks should not be updated after creation for regular users
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailyStockCheck $dailyStockCheck): bool
    {
        // Admins can delete stock checks
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner and manager can delete stock checks
        if (!$user->shops()->where('shops.id', $dailyStockCheck->shop_id)->exists()) {
            return false;
        }
        
        return $user->hasAnyRole(['owner', 'manager'], $dailyStockCheck->shop_id);
    }
}