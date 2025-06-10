<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionAdminMiddlewareManualTest extends TestCase
{
    public function test_admin_route_uses_correct_middleware()
    {
        // Check if the route exists
        $this->assertTrue(
            \Illuminate\Support\Facades\Route::has('admin.subscriptions.index'),
            'The admin.subscriptions.index route does not exist'
        );
        
        echo "Route check passed: admin.subscriptions.index route exists.\n";
        
        // Get the route middleware
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        foreach ($routes as $route) {
            if ($route->getName() === 'admin.subscriptions.index') {
                $middleware = $route->middleware();
                echo "Route middleware for admin.subscriptions.index: " . implode(', ', $middleware) . "\n";
                $this->assertContains('subscription-admin', $middleware, 'The route does not use the subscription-admin middleware');
                break;
            }
        }
    }
}
}
