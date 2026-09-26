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
        Schema::create('site_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('x_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('weekday_label');
            $table->string('weekday_hours');
            $table->string('saturday_label');
            $table->string('saturday_hours');
            $table->string('extra_label');
            $table->string('extra_hours');
            $table->string('gallery_1')->nullable();
            $table->string('gallery_2')->nullable();
            $table->string('gallery_3')->nullable();
            $table->string('gallery_4')->nullable();
            $table->string('gallery_5')->nullable();
            $table->string('gallery_6')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_profiles');
    }
};
