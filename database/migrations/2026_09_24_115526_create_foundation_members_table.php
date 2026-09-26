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
        Schema::create('foundation_members', function (Blueprint $table) {
            $table->id();
            $table->string('member_number', 20)->unique();
            $table->string('full_name');
            $table->string('photo');
            $table->string('gender', 20);
            $table->date('date_of_birth');
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('city', 80);
            $table->string('state', 80)->default('Imo State');
            $table->string('occupation')->nullable();
            $table->date('joined_on');
            $table->string('status', 20)->default('active');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 40)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('full_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foundation_members');
    }
};
