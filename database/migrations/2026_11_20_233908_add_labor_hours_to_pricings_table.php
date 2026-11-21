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
         Schema::table('pricings', function (Blueprint $table) {
        $table->decimal('hourly_rate', 10, 2)->default(8.00)->after('materials_cost');
        $table->decimal('hours_spent', 5, 2)->default(0.25)->after('hourly_rate');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricings', function (Blueprint $table) {
          $table->dropColumn(['hourly_rate', 'hours_spent']);
        });
    }
};
