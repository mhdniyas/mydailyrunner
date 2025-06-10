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
        // Tables that might already have user_id based on checking models
        // but we'll add a check to prevent errors
        
        // Add user_id to products table if it doesn't exist
        if (!Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            });
        }
        
        // Add user_id to stock_ins table if it doesn't exist
        if (!Schema::hasColumn('stock_ins', 'user_id')) {
            Schema::table('stock_ins', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            });
        }
        
        // Add user_id to daily_stock_checks table if it doesn't exist
        if (!Schema::hasColumn('daily_stock_checks', 'user_id')) {
            Schema::table('daily_stock_checks', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            });
        }
        
        // Add user_id to customers table if it doesn't exist
        if (!Schema::hasColumn('customers', 'user_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            });
        }
        
        // Add user_id to sales table if it doesn't exist
        if (!Schema::hasColumn('sales', 'user_id')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            });
        }
        
        // Add user_id to sale_items table if it doesn't exist
        if (!Schema::hasColumn('sale_items', 'user_id')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('sale_id')->constrained()->nullOnDelete();
            });
        }
        
        // Add user_id to payments table if it doesn't exist
        if (!Schema::hasColumn('payments', 'user_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('sale_id')->constrained()->nullOnDelete();
            });
        }
        
        // Add user_id to financial_categories table if it doesn't exist
        if (!Schema::hasColumn('financial_categories', 'user_id')) {
            Schema::table('financial_categories', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            });
        }
        
        // Add user_id to financial_entries table if it doesn't exist
        if (!Schema::hasColumn('financial_entries', 'user_id')) {
            Schema::table('financial_entries', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't remove these columns for safety reasons
        // If needed, create a separate migration to remove them
    }
};
