<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add a database entry to set a user as admin
        DB::table('shop_users')
            ->where('role', 'owner')
            ->limit(1)
            ->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert admin back to owner
        DB::table('shop_users')
            ->where('role', 'admin')
            ->update(['role' => 'owner']);
    }
};
