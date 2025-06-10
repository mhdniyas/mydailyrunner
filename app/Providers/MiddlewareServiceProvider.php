<?php

namespace App\Providers;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Kernel $kernel): void
    {
        // Register middleware aliases manually
        $router = $this->app['router'];
        
        $router->aliasMiddleware('shop.selected', \App\Http\Middleware\EnsureShopSelected::class);
        $router->aliasMiddleware('owner', \App\Http\Middleware\EnsureUserIsOwner::class);
        $router->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        $router->aliasMiddleware('subscribed', \App\Http\Middleware\SubscriptionMiddleware::class);
    }
}
