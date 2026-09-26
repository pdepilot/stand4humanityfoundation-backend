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
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_status', 20)->default('paid')->after('payment_method');
            $table->string('provider_reference')->nullable()->unique()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('provider_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique(['provider_reference']);
            $table->dropColumn(['payment_status', 'provider_reference', 'paid_at']);
        });
    }
};
