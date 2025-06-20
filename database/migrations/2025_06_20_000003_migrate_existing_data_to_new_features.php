<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\StockIn;
use App\Models\StockBatch;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Convert existing subscription data
        foreach (User::all() as $user) {
            if ($user->is_subscribed && $user->is_admin_approved) {
                $user->subscription_status = 'active';
                $user->subscription_expires_at = now()->addDays(30); // Default 30 days
            } elseif ($user->is_subscribed && !$user->is_admin_approved) {
                $user->subscription_status = 'pending';
            } else {
                $user->subscription_status = 'expired';
            }
            $user->save();
        }

        // 2. Create batch records for existing stock-ins
        foreach (StockIn::all() as $stockIn) {
            $stockIn->createBatch([
                'batch_date' => $stockIn->created_at->format('Y-m-d'),
                'notes' => 'Migrated from existing stock-in record',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Revert subscription data
        foreach (User::all() as $user) {
            if ($user->subscription_status === 'active') {
                $user->is_subscribed = true;
                $user->is_admin_approved = true;
            } elseif ($user->subscription_status === 'pending') {
                $user->is_subscribed = true;
                $user->is_admin_approved = false;
            } else {
                $user->is_subscribed = false;
                $user->is_admin_approved = false;
            }
            $user->save();
        }

        // 2. We won't attempt to delete the batch records in the down migration
        // since that could cause data loss.
    }
};
