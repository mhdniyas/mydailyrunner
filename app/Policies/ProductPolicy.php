<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any user with access to the shop can view products
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        // Admins can view any product
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has access to the shop that owns this product
        return $user->shops()->where('shops.id', $product->shop_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins can create products
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner, manager, and stock roles can create products
        return $user->hasAnyRole(['owner', 'manager', 'stock']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        // Admins can update any product
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has appropriate role in the shop that owns this product
        if (!$user->shops()->where('shops.id', $product->shop_id)->exists()) {
            return false;
        }
        
        return $user->hasAnyRole(['owner', 'manager', 'stock'], $product->shop_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        // Admins can delete any product
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only owner and manager can delete products
        if (!$user->shops()->where('shops.id', $product->shop_id)->exists()) {
            return false;
        }
        
        return $user->hasAnyRole(['owner', 'manager'], $product->shop_id);
    }
}