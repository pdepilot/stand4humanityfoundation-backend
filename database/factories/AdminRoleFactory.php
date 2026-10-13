<?php

namespace Database\Factories;

use App\Enums\PortalPermission;
use App\Models\AdminRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdminRole>
 */
class AdminRoleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'News desk',
            'permissions' => [PortalPermission::News->value],
        ];
    }
}
