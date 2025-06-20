<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing boolean fields
            $table->dropColumn(['is_subscribed', 'is_admin_approved']);
            
            // Add new subscription_status field
            $table->string('subscription_status')->default('pending')->after('remember_token');
            $table->date('subscription_expires_at')->nullable()->after('subscription_status');
            $table->text('subscription_notes')->nullable()->after('subscription_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscription_status', 'subscription_expires_at', 'subscription_notes']);
            $table->boolean('is_subscribed')->default(false)->after('remember_token');
            $table->boolean('is_admin_approved')->default(false)->after('is_subscribed');
        });
    }
};
