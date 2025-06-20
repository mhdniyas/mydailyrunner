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
        
        // Auto-update expired status if needed
        if ($user->subscription_status === 'active' && 
            $user->subscription_expires_at !== null && 
            $user->subscription_expires_at < now()) {
            $user->subscription_status = 'expired';
            $user->save();
        }
        
        // Check subscription status
        if ($user->subscription_status === 'active') {
            // Show warning for expiring soon (7 days or less)
            if ($user->needsExpirationWarning()) {
                $daysLeft = $user->daysRemainingInSubscription();
                $warningMessage = "Your subscription expires in {$daysLeft} " . 
                                 ($daysLeft == 1 ? 'day' : 'days') . 
                                 ". Please renew soon to avoid service interruption.";
                
                session()->flash('subscription_warning', $warningMessage);
            }
            
            return $next($request);
        } else if ($user->subscription_status === 'grace_period') {
            // Allow access but with a warning
            session()->flash('subscription_warning', 
                'Your subscription has expired but you are in a grace period. ' .
                'Please renew your subscription as soon as possible.');
            
            return $next($request);
        } else if ($user->subscription_status === 'pending') {
            return redirect()->route('subscription.status')
                ->with('warning', 'Your subscription is pending approval. Please check back later.');
        } else {
            // Expired or other status - redirect to subscription page
            return redirect()->route('subscription.status')
                ->with('error', 'Your subscription has expired. Please renew to continue using the application.');
        }
    }
}
