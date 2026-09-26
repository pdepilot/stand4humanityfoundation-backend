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
        Schema::table('donation_settings', function (Blueprint $table) {
            $table->decimal('child_protection_goal', 14, 2)->default(0);
            $table->decimal('rescue_goal', 14, 2)->default(0);
            $table->decimal('victim_support_goal', 14, 2)->default(0);
            $table->dropColumn('goal_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_settings', function (Blueprint $table) {
            $table->decimal('goal_amount', 14, 2)->default(0);
            $table->dropColumn([
                'child_protection_goal',
                'rescue_goal',
                'victim_support_goal',
            ]);
        });
    }
};
