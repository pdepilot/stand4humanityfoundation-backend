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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name', 80);
            $table->string('email', 120);
            $table->string('phone', 40);
            $table->string('location', 120)->nullable();
            $table->string('description', 200)->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3);
            $table->string('cause', 60);
            $table->string('frequency', 20);
            $table->string('payment_method', 40);
            $table->boolean('anonymous')->default(false);
            $table->string('source', 20);
            $table->timestamps();

            $table->index(['created_at', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
