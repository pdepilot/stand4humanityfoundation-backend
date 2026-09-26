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
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('country', 80)->nullable()->after('email');
            $table->string('country_code', 2)->nullable()->after('country');
            $table->string('city', 80)->nullable()->after('country_code');
            $table->string('ip_address', 45)->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn(['country', 'country_code', 'city', 'ip_address']);
        });
    }
};
