<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'subscribed' => \App\Http\Middleware\SubscriptionMiddleware::class,
            'subscription-admin' => \App\Http\Middleware\SubscriptionAdminMiddleware::class,
            'shop.selected' => \App\Http\Middleware\EnsureShopSelected::class,
            'shop.role' => \App\Http\Middleware\CheckShopRole::class,
            'owner' => \App\Http\Middleware\CheckShopRole::class.':owner',
            'manager' => \App\Http\Middleware\CheckShopRole::class.':owner,manager',
            'finance' => \App\Http\Middleware\CheckShopRole::class.':owner,manager,finance',
            'stock' => \App\Http\Middleware\CheckShopRole::class.':owner,manager,stock',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
