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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('ration_card_number')->nullable()->after('address');
            $table->enum('card_type', ['AAY', 'PHH', 'NPS', 'NPI', 'NPNS'])->nullable()->after('ration_card_number');
            $table->text('notes')->nullable()->after('card_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['ration_card_number', 'card_type', 'notes']);
        });
    }
};