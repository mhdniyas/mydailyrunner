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
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->enum('calculation_method', ['formula_minus_half', 'formula_direct', 'manual'])->default('formula_minus_half')->after('avg_bag_weight');
            $table->decimal('manual_bag_weight', 10, 2)->nullable()->after('calculation_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->dropColumn(['calculation_method', 'manual_bag_weight']);
        });
    }
};
