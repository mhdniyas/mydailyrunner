<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Always allow access to subscription management routes
        if ($request->routeIs('subscription.*')) {
            return $next($request);
        }
        
        // Allow super admins to bypass subscription requirements
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        // Check if user has an active subscription and admin approval
        if (!$user->is_subscribed || !$user->is_admin_approved) {
            return redirect()->route('subscription.status')
                ->with('warning', 'You need an active subscription to access this feature.');
        }

        return $next($request);
    }
}
