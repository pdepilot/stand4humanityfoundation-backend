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
            $table->decimal('spent_amount', 14, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_settings', function (Blueprint $table) {
            $table->dropColumn('spent_amount');
        });
    }
};
