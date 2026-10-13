<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_profiles', function (Blueprint $table) {
            $table->string('impact_video_url')->nullable()->after('linkedin_url');
            $table->string('impact_video_caption')->nullable()->after('impact_video_url');
        });

        DB::table('site_profiles')->whereNull('impact_video_url')->update([
            'impact_video_url' => 'https://www.youtube.com/watch?v=DWRcNpR6Kdc',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * Dropping these columns removes the saved video address and banner sentence.
     */
    public function down(): void
    {
        Schema::table('site_profiles', function (Blueprint $table) {
            $table->dropColumn(['impact_video_url', 'impact_video_caption']);
        });
    }
};
