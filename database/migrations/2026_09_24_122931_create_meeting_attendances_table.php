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
        Schema::create('meeting_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('foundation_member_id')->constrained()->cascadeOnDelete();
            $table->date('meeting_on');
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('marked_at');
            $table->timestamps();

            $table->unique(['foundation_member_id', 'meeting_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_attendances');
    }
};
