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
        Schema::create('incident_report_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_report_id')->constrained()->cascadeOnDelete();
            $table->string('disk', 40);
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 120)->nullable();
            $table->unsignedInteger('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_report_files');
    }
};
