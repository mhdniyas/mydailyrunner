<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckShopRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $shopId = session('current_shop_id');
        
        if (!$shopId) {
            return redirect()->route('shops.select')
                ->with('error', 'Please select a shop to continue.');
        }
        
        $user = Auth::user();
        
        if (!$user->hasAnyRole($roles, $shopId)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        return $next($request);
    }
}