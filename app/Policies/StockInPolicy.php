<?php

namespace App\Policies;

use App\Models\StockIn;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockInPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any user with access to the shop can view stock ins
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StockIn $stockIn): bool
    {
        // Admins can view any stock in
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has access to the shop that owns this stock in
        return $user->shops()->where('shops.id', $stockIn->shop_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins can create stock ins
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner, manager, and stock roles can create stock ins
        return $user->hasAnyRole(['owner', 'manager', 'stock']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StockIn $stockIn): bool
    {
        // Admins can update any stock in
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has appropriate role in the shop that owns this stock in
        if (!$user->shops()->where('shops.id', $stockIn->shop_id)->exists()) {
            return false;
        }
        
        // Owner, manager, and stock roles can update stock ins
        return $user->hasAnyRole(['owner', 'manager', 'stock'], $stockIn->shop_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StockIn $stockIn): bool
    {
        // Admins can delete any stock in
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner and manager can delete stock ins
        if (!$user->shops()->where('shops.id', $stockIn->shop_id)->exists()) {
            return false;
        }
        
        return $user->hasAnyRole(['owner', 'manager'], $stockIn->shop_id);
    }
}