<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins can view all users
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admins can view any user
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->hasRole('owner') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins can create users
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admins can update any user
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->hasRole('owner') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Prevent users from deleting themselves
        if ($user->id === $model->id) {
            return false;
        }
        
        // Admins can delete any user (except themselves)
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can manage roles.
     */
    public function manageRoles(User $user, User $model): bool
    {
        // Admins can manage any user's roles
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can send invitations.
     */
    public function invite(User $user, User $model): bool
    {
        // Admins can send invitations
        if ($user->isAdmin()) {
            return true;
        }
        
        return $user->hasRole('owner');
    }
}