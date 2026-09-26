<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => 'Programme lead',
            'photo' => 'team/portrait.jpg',
            'facebook_url' => null,
            'x_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
            'sort_order' => fake()->unique()->numberBetween(1, 99999),
        ];
    }
}
