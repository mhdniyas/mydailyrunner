<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'invitation_token',
        'last_login_at',
        'subscription_status',
        'subscription_expires_at',
        'subscription_notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'subscription_expires_at' => 'date',
    ];

    /**
     * Get the shops associated with the user.
     */
    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the shop user records.
     */
    public function shopUsers()
    {
        return $this->hasMany(ShopUser::class);
    }

    /**
     * Get the shops owned by the user.
     */
    public function ownedShops()
    {
        return $this->hasMany(Shop::class, 'owner_id');
    }

    /**
     * Check if user has a specific role in a shop.
     */
    public function hasRole($role, $shopId = null)
    {
        // Admins bypass role checks
        if ($this->isAdmin()) {
            return true;
        }
        
        if (!$shopId) {
            $shopId = session('current_shop_id');
        }

        if (!$shopId) {
            return false;
        }

        $shopUser = $this->shopUsers()
            ->where('shop_id', $shopId)
            ->first();

        if (!$shopUser) {
            return false;
        }

        if ($role === 'any') {
            return true;
        }

        return $shopUser->role === $role;
    }

    /**
     * Check if user has any of the specified roles in a shop.
     */
    public function hasAnyRole(array $roles, $shopId = null)
    {
        // Admins bypass role checks
        if ($this->isAdmin()) {
            return true;
        }
        
        if (!$shopId) {
            $shopId = session('current_shop_id');
        }

        if (!$shopId) {
            return false;
        }

        $shopUser = $this->shopUsers()
            ->where('shop_id', $shopId)
            ->first();

        if (!$shopUser) {
            return false;
        }

        return in_array($shopUser->role, $roles);
    }

    /**
     * Check if user is a system administrator.
     * 
     * @return bool
     */
    public function isAdmin()
    {
        // In this implementation, we're considering users with 'admin' role
        // in any shop to be system administrators
        return $this->shopUsers()->where('role', 'admin')->exists();
    }
    
    /**
     * Check if user has subscription approval rights.
     * 
     * @return bool
     */
    public function canApproveSubscriptions()
    {
        // Only users with admin role can approve subscriptions
        return $this->isAdmin();
    }

    /**
     * Check if user has an active subscription.
     * 
     * @return bool
     */
    public function hasActiveSubscription()
    {
        return $this->subscription_status === 'active' && 
               ($this->subscription_expires_at === null || $this->subscription_expires_at >= now());
    }

    /**
     * Check if user is in grace period.
     * 
     * @return bool
     */
    public function isInGracePeriod()
    {
        return $this->subscription_status === 'grace_period';
    }

    /**
     * Check if user's subscription has expired.
     * 
     * @return bool
     */
    public function hasExpiredSubscription()
    {
        return $this->subscription_status === 'expired' || 
               ($this->subscription_status === 'active' && $this->subscription_expires_at !== null && $this->subscription_expires_at < now());
    }

    /**
     * Get days remaining in subscription.
     * 
     * @return int|null
     */
    public function daysRemainingInSubscription()
    {
        if ($this->subscription_expires_at === null) {
            return null; // Unlimited subscription
        }
        
        return max(0, now()->diffInDays($this->subscription_expires_at, false));
    }

    /**
     * Check if user needs to be warned about expiring subscription.
     * 
     * @return bool
     */
    public function needsExpirationWarning()
    {
        if ($this->subscription_expires_at === null) {
            return false;
        }
        
        $daysRemaining = $this->daysRemainingInSubscription();
        return $this->subscription_status === 'active' && $daysRemaining !== null && $daysRemaining <= 7;
    }
}