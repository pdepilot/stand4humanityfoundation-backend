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
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->string('status', 20);
            $table->string('source', 20);
            $table->string('email', 120)->unique();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
