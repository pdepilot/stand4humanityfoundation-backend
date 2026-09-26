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
        Schema::create('volunteer_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->string('status', 20);
            $table->string('source', 20);
            $table->string('name', 80);
            $table->string('email', 120);
            $table->string('phone', 40);
            $table->string('city', 80);
            $table->string('interest', 40);
            $table->string('availability', 120);
            $table->text('experience')->nullable();
            $table->text('motivation');
            $table->string('photo', 255);
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'id']);
            $table->index('status');
            $table->index('interest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
