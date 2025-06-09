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
}