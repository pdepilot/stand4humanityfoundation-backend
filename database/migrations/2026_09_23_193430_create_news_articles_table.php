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
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 120)->unique();
            $table->string('title', 200);
            $table->string('excerpt', 400);
            $table->text('body');
            $table->string('image', 160);
            $table->string('image_alt', 200);
            $table->string('author', 80);
            $table->unsignedTinyInteger('reading_minutes')->default(4);
            $table->json('tags')->nullable();
            $table->string('search_keywords', 500)->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->timestamp('published_at');
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
            $table->index(['is_popular', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
