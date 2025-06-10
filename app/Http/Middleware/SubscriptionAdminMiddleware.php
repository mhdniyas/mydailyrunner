<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has the admin role
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Only super administrators with the admin role can approve subscription requests.');
        }

        return $next($request);
    }
}
