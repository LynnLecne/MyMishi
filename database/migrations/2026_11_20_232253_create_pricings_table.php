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
           Schema::create('pricings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
        $table->decimal('materials_cost', 10, 2)->default(0);
        $table->decimal('labor_cost', 10, 2)->default(0);
        $table->decimal('packaging_cost', 10, 2)->default(0);
        $table->decimal('profit_margin', 5, 2)->default(20);
        $table->decimal('final_price', 10, 2)->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricings');
    }
};
