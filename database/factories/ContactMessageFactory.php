<?php

namespace Database\Factories;

use App\Enums\ContactSource;
use App\Enums\ContactStatus;
use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => 'cnt-'.Str::lower(bin2hex(random_bytes(6))),
            'status' => ContactStatus::New,
            'source' => ContactSource::Public,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => null,
            'subject' => 'Child protection help',
            'message' => 'We need guidance on how to request assistance for a family in Owerri.',
        ];
    }

    public function fromDesk(): static
    {
        return $this->state(fn (array $attributes): array => [
            'source' => ContactSource::Desk,
        ]);
    }
}
