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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('suspended_at')->nullable()->after('role');
        });

        $email = config('portal.admin.email');

        if (is_string($email) && $email !== '') {
            DB::table('users')
                ->where('email', $email)
                ->update(['role' => 'super-administrator']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('suspended_at');
        });
    }
};
