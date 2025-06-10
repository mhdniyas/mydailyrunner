<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShopPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins can view all shops
        if ($user->isAdmin()) {
            return true;
        }
        
        // If no shops exist yet, allow first user to create one
        if (Shop::count() === 0) {
            return true;
        }
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shop $shop): bool
    {
        // Admins can view any shop
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has any role in this shop
        return $user->shops()->where('shops.id', $shop->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins can create shops
        if ($user->isAdmin()) {
            return true;
        }
        
        // If no shops exist yet, allow first user to create one
        if (Shop::count() === 0) {
            return true;
        }
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shop $shop): bool
    {
        // Admins can update any shop
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->id === $shop->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shop $shop): bool
    {
        // Admins can delete any shop
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->id === $shop->owner_id;
    }
}