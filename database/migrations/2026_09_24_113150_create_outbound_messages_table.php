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
        Schema::create('outbound_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sent_by')->constrained('users')->cascadeOnDelete();
            $table->string('template', 40);
            $table->string('audience', 40);
            $table->string('recipient_email')->nullable();
            $table->string('subject', 200);
            $table->text('body');
            $table->json('attachment_names')->nullable();
            $table->unsignedInteger('recipient_count');
            $table->timestamp('sent_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_messages');
    }
};
