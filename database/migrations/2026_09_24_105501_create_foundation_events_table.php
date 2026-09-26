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
        Schema::create('foundation_events', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 120)->unique();
            $table->string('title', 200);
            $table->string('summary', 400);
            $table->text('description');
            $table->string('image', 160);
            $table->string('image_alt', 200);
            $table->string('location', 160);
            $table->string('audience', 80)->nullable();
            $table->json('highlights')->nullable();
            $table->string('action', 40);
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'starts_at']);
            $table->index(['is_featured', 'starts_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foundation_events');
    }
};
