<?php

namespace App\Providers;

use App\Http\Middleware\EnsureShopSelected;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Explicitly bind the middleware to resolve the class loading issue
        $this->app->singleton('middleware.shop.selected', function ($app) {
            return new EnsureShopSelected();
        });
        
        // Direct binding for the middleware class
        $this->app->bind(\App\Http\Middleware\EnsureShopSelected::class, function ($app) {
            return new \App\Http\Middleware\EnsureShopSelected();
        });
        
        // Bind AdminMiddleware
        $this->app->bind(\App\Http\Middleware\AdminMiddleware::class, function ($app) {
            return new \App\Http\Middleware\AdminMiddleware();
        });
        
        // Bind SubscriptionMiddleware
        $this->app->bind(\App\Http\Middleware\SubscriptionMiddleware::class, function ($app) {
            return new \App\Http\Middleware\SubscriptionMiddleware();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
