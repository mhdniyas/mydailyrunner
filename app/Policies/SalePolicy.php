<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any user with access to the shop can view sales
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $sale): bool
    {
        // Check if user has access to the shop that owns this sale
        return $user->shops->contains($sale->shop_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only owner, manager, and finance roles can create sales
        return $user->hasAnyRole(['owner', 'manager', 'finance']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sale $sale): bool
    {
        // Check if user has appropriate role in the shop that owns this sale
        if (!$user->shops->contains($sale->shop_id)) {
            return false;
        }
        
        // Only owner, manager, and finance can update sales
        return $user->hasAnyRole(['owner', 'manager', 'finance'], $sale->shop_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sale $sale): bool
    {
        // Only owner and manager can delete sales
        if (!$user->shops->contains($sale->shop_id)) {
            return false;
        }
        
        return $user->hasAnyRole(['owner', 'manager'], $sale->shop_id);
    }
}