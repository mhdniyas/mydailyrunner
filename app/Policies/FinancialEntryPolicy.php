<?php

namespace App\Policies;

use App\Models\FinancialEntry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FinancialEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins can view any financial entries
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner, manager, and finance roles can view financial entries
        return $user->hasAnyRole(['owner', 'manager', 'finance']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FinancialEntry $financialEntry): bool
    {
        // Admins can view any financial entry
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has appropriate role in the shop that owns this entry
        if (!$user->shops()->where('shops.id', $financialEntry->shop_id)->exists()) {
            return false;
        }
        
        return $user->hasAnyRole(['owner', 'manager', 'finance'], $financialEntry->shop_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins can create financial entries
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner, manager, and finance roles can create financial entries
        return $user->hasAnyRole(['owner', 'manager', 'finance']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FinancialEntry $financialEntry): bool
    {
        // Admins can update any financial entry
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has appropriate role in the shop that owns this entry
        if (!$user->shops()->where('shops.id', $financialEntry->shop_id)->exists()) {
            return false;
        }
        
        // Only owner and manager can update financial entries
        return $user->hasAnyRole(['owner', 'manager'], $financialEntry->shop_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FinancialEntry $financialEntry): bool
    {
        // Admins can delete any financial entry
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner and manager can delete financial entries
        if (!$user->shops()->where('shops.id', $financialEntry->shop_id)->exists()) {
            return false;
        }
        
        return $user->hasAnyRole(['owner', 'manager'], $financialEntry->shop_id);
    }
}