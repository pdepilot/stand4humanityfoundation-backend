<?php

namespace Database\Factories;

use App\Enums\MemberGender;
use App\Enums\MembershipStatus;
use App\Models\FoundationMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FoundationMember>
 */
class FoundationMemberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_number' => 'SFH-'.str_pad((string) fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'full_name' => fake()->name(),
            'photo' => 'members/portrait.jpg',
            'gender' => MemberGender::Female,
            'date_of_birth' => fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'phone' => '08031234567',
            'email' => fake()->unique()->safeEmail(),
            'address' => '12 Wetheral Road',
            'city' => 'Owerri',
            'state' => 'Imo',
            'occupation' => 'Teacher',
            'joined_on' => now()->subYear()->toDateString(),
            'status' => MembershipStatus::Active,
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => '08039876543',
            'notes' => null,
        ];
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => MembershipStatus::Suspended,
        ]);
    }
}
