<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\DailyStockCheck;
use App\Models\Sale;
use App\Models\FinancialEntry;
use App\Policies\UserPolicy;
use App\Policies\ShopPolicy;
use App\Policies\ProductPolicy;
use App\Policies\StockInPolicy;
use App\Policies\DailyStockCheckPolicy;
use App\Policies\SalePolicy;
use App\Policies\FinancialEntryPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Shop::class => ShopPolicy::class,
        Product::class => ProductPolicy::class,
        StockIn::class => StockInPolicy::class,
        DailyStockCheck::class => DailyStockCheckPolicy::class,
        Sale::class => SalePolicy::class,
        FinancialEntry::class => FinancialEntryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define role-based gates
        Gate::define('owner', function (User $user) {
            return $user->hasRole('owner');
        });
        
        Gate::define('manager', function (User $user) {
            return $user->hasAnyRole(['owner', 'manager']);
        });
        
        Gate::define('finance', function (User $user) {
            return $user->hasAnyRole(['owner', 'manager', 'finance']);
        });
        
        Gate::define('stock', function (User $user) {
            return $user->hasAnyRole(['owner', 'manager', 'stock']);
        });
        
        Gate::define('view-reports', function (User $user) {
            return $user->hasAnyRole(['owner', 'manager', 'finance']);
        });
    }
}