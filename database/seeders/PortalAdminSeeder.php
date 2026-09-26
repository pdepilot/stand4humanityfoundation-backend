<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class PortalAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('portal.admin.email');
        $name = config('portal.admin.name');
        $password = config('portal.admin.password');

        if (! is_string($email) || $email === '' || ! is_string($name) || $name === '') {
            throw new RuntimeException('Portal admin name and email must be configured.');
        }

        if (! is_string($password) || $password === '') {
            throw new RuntimeException('Set PORTAL_ADMIN_PASSWORD in the environment before seeding.');
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'role' => UserRole::SuperAdministrator,
                'email_verified_at' => now(),
            ],
        );
    }
}
