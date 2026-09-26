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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->string('status', 20);
            $table->string('source', 20);
            $table->string('name', 80);
            $table->string('email', 120);
            $table->string('phone', 40)->nullable();
            $table->string('subject', 160);
            $table->text('message');
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
        Schema::dropIfExists('contact_messages');
    }
};
