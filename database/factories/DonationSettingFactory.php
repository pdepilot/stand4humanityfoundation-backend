<?php

namespace Database\Factories;

use App\Models\DonationSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DonationSetting>
 */
class DonationSettingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_protection_goal' => 10000,
            'rescue_goal' => 10000,
            'victim_support_goal' => 10000,
            'spent_amount' => 0,
        ];
    }
}
