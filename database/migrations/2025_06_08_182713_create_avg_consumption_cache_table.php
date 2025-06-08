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
        Schema::create('avg_consumption_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('avg_per_day', 10, 4);
            $table->decimal('total_consumed', 10, 2);
            $table->integer('days_tracked');
            $table->date('last_calculated');
            $table->timestamps();
            
            $table->unique('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avg_consumption_cache');
    }
};
