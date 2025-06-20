<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display subscription status.
     *
     * @return \Illuminate\Http\Response
     */
    public function status()
    {
        $user = Auth::user();
        
        return view('subscription.status', compact('user'));
    }

    /**
     * Show subscription request form.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestForm()
    {
        $user = Auth::user();
        
        // Redirect if already has a subscription or pending request
        if (in_array($user->subscription_status, ['active', 'pending', 'grace_period'])) {
            return redirect()->route('subscription.status')
                ->with('info', 'You already have an active subscription or pending request.');
        }
        
        return view('subscription.request', compact('user'));
    }

    /**
     * Submit subscription request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitRequest(Request $request)
    {
        $user = Auth::user();
        
        // Update user subscription status
        $user->subscription_status = 'pending';
        $user->subscription_notes = $request->input('notes');
        $user->save();
        
        // Log the subscription request
        \Log::info('Subscription requested by: ' . $user->name . ' (ID: ' . $user->id . ')');
        
        // Notify admins about the new subscription request
        $admins = User::whereHas('shopUsers', function($query) {
            $query->where('role', 'admin');
        })->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SubscriptionStatusChanged('requested', $user));
        }
        
        return redirect()->route('subscription.status')
            ->with('success', 'Your subscription request has been submitted and is pending approval.');
    }

    /**
     * Cancel subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        $user = Auth::user();
        
        // Store the previous status for notifications
        $previousStatus = $user->subscription_status;
        
        // Cancel subscription
        $user->subscription_status = 'expired';
        $user->subscription_expires_at = now();
        $user->save();
        
        // Log the cancellation
        \Log::info('Subscription cancelled by user: ' . $user->name . ' (ID: ' . $user->id . ')');
        
        // If this was an approved subscription, notify admins about the cancellation
        if ($wasApproved) {
            $admins = User::whereHas('shopUsers', function($query) {
                $query->where('role', 'admin');
            })->get();
            
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\SubscriptionStatusChanged('user_cancelled', $user));
            }
        }
        
        return redirect()->route('subscription.status')
            ->with('success', 'Your subscription has been cancelled.');
    }
}
