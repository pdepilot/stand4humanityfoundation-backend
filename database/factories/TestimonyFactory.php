<?php

namespace Database\Factories;

use App\Enums\TestimonyStatus;
use App\Models\Testimony;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimony>
 */
class TestimonyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'Parent',
            'body' => 'The foundation stood with our family when we had nowhere else to turn.',
            'rating' => 5,
            'photo' => null,
            'status' => TestimonyStatus::Pending,
            'reviewed_at' => null,
            'reviewed_by' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TestimonyStatus::Approved,
            'reviewed_at' => now(),
        ]);
    }
}
