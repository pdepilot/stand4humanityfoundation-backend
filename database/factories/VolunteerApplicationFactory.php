<?php

namespace Database\Factories;

use App\Enums\VolunteerInterest;
use App\Enums\VolunteerSource;
use App\Enums\VolunteerStatus;
use App\Models\VolunteerApplication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<VolunteerApplication>
 */
class VolunteerApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => 'vol-'.Str::lower(bin2hex(random_bytes(6))),
            'status' => VolunteerStatus::Pending,
            'source' => VolunteerSource::Public,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '08012345678',
            'city' => 'Owerri',
            'interest' => VolunteerInterest::ChildProtection,
            'availability' => 'Weekends',
            'experience' => 'Community outreach with local youth groups.',
            'motivation' => 'I want to stand with children and families who need a steady helping hand in Owerri.',
            'photo' => 'volunteers/example.jpg',
            'admin_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    public function fromDesk(): static
    {
        return $this->state(fn (array $attributes): array => [
            'source' => VolunteerSource::Desk,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => VolunteerStatus::Approved,
            'reviewed_at' => now(),
        ]);
    }

    public function declined(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => VolunteerStatus::Declined,
            'reviewed_at' => now(),
        ]);
    }
}
