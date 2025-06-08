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
        Schema::create('personal_finance_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('finance_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('entry_date');
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['income', 'expense']);
            $table->string('description');
            $table->text('remarks')->nullable();
            $table->string('reference_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_finance_entries');
    }
};
