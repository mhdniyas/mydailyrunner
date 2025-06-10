<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubscriptionStatusChanged;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        // Check if the user is a super admin in middleware
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'You do not have permission to access this page. Only super admins can manage subscriptions.');
            }
            
            // Log the admin access for auditing purposes
            \Log::info('Admin access to subscription management by: ' . auth()->user()->name . ' (ID: ' . auth()->user()->id . ')');
            
            return $next($request);
        });
    }

    /**
     * Display a listing of the users with subscription status.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscriptions()
    {
        $users = User::orderBy('is_subscribed', 'desc')
            ->orderBy('is_admin_approved', 'desc')
            ->paginate(20);
        
        return view('admin.subscriptions.index', compact('users'));
    }

    /**
     * Display pending subscription approvals.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingApprovals()
    {
        $users = User::where('is_subscribed', true)
            ->where('is_admin_approved', false)
            ->paginate(20);
        
        return view('admin.subscriptions.pending', compact('users'));
    }

    /**
     * Approve a user's subscription.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function approveSubscription(User $user)
    {
        // Double-check that current user can approve subscriptions
        if (!auth()->user()->canApproveSubscriptions()) {
            return redirect()->route('admin.subscriptions.pending')
                ->with('error', 'You do not have permission to approve subscriptions.');
        }
        
        // Update user subscription status
        $user->is_admin_approved = true;
        $user->save();
        
        // Log the approval
        \Log::info('Subscription approved for user: ' . $user->name . ' (ID: ' . $user->id . ') by admin: ' . auth()->user()->name);
        
        // Send notification to user
        $user->notify(new SubscriptionStatusChanged('approved'));
        
        return redirect()->route('admin.subscriptions.pending')
            ->with('success', 'Subscription approved successfully for ' . $user->name);
    }

    /**
     * Reject a user's subscription.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function rejectSubscription(User $user)
    {
        // Double-check that current user can reject subscriptions
        if (!auth()->user()->canApproveSubscriptions()) {
            return redirect()->route('admin.subscriptions.pending')
                ->with('error', 'You do not have permission to reject subscriptions.');
        }
        
        // Update subscription status
        $user->is_subscribed = false;
        $user->is_admin_approved = false;
        $user->save();
        
        // Log the rejection
        \Log::info('Subscription rejected for user: ' . $user->name . ' (ID: ' . $user->id . ') by admin: ' . auth()->user()->name);
        
        // Send notification to user
        $user->notify(new SubscriptionStatusChanged('rejected'));
        
        return redirect()->route('admin.subscriptions.pending')
            ->with('success', 'Subscription rejected for ' . $user->name);
    }

    /**
     * Toggle a user's subscription status.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function toggleSubscription(User $user)
    {
        // Double-check that current user can manage subscriptions
        if (!auth()->user()->canApproveSubscriptions()) {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'You do not have permission to manage subscriptions.');
        }
        
        $action = '';
        
        if ($user->is_subscribed) {
            $user->is_subscribed = false;
            $user->is_admin_approved = false;
            $status = 'cancelled';
            $action = 'cancelled';
        } else {
            $user->is_subscribed = true;
            $user->is_admin_approved = true;
            $status = 'approved';
            $action = 'activated';
        }
        
        $user->save();
        
        // Log the action
        \Log::info('Subscription ' . $action . ' for user: ' . $user->name . ' (ID: ' . $user->id . ') by admin: ' . auth()->user()->name);
        
        // Send notification to user
        $user->notify(new SubscriptionStatusChanged($status));
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription ' . $action . ' successfully for ' . $user->name);
    }
}
