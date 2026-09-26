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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->string('status', 40);
            $table->string('source', 20);
            $table->string('full_name', 120)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('alt_contact', 120)->nullable();
            $table->string('relationship', 40);
            $table->string('incident_type', 80);
            $table->string('incident_title', 160);
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->string('state', 40);
            $table->string('lga', 80)->nullable();
            $table->string('community', 120)->nullable();
            $table->string('full_address', 200)->nullable();
            $table->text('description');
            $table->string('victim_name', 120)->nullable();
            $table->unsignedTinyInteger('victim_age')->nullable();
            $table->string('victim_gender', 40)->nullable();
            $table->string('victim_phone', 40)->nullable();
            $table->string('victim_last_location', 160)->nullable();
            $table->string('suspect_name', 120)->nullable();
            $table->string('suspect_alias', 80)->nullable();
            $table->string('suspect_description', 200)->nullable();
            $table->string('suspect_address', 200)->nullable();
            $table->string('suspect_vehicle', 160)->nullable();
            $table->text('suspect_notes')->nullable();
            $table->string('incident_address', 200)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('consented_at');
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
        Schema::dropIfExists('incident_reports');
    }
};
