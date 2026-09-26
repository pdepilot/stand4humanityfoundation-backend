<?php

namespace Database\Factories;

use App\Enums\EventAction;
use App\Models\FoundationEvent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<FoundationEvent>
 */
class FoundationEventFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Community gathering '.fake()->unique()->numberBetween(1, 9999);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'summary' => 'A public gathering for families and volunteers in Owerri, Imo State.',
            'description' => 'Families, volunteers, and neighbours meet to learn how the foundation protects children and supports people in need.',
            'image' => 'event-1.jpg',
            'image_alt' => 'Community gathering in Owerri',
            'location' => 'Owerri, Imo State',
            'audience' => 'Open to the public',
            'highlights' => ['Child protection information', 'Volunteer onboarding'],
            'action' => EventAction::Contact,
            'starts_at' => now()->addWeek()->setTime(9, 0),
            'ends_at' => now()->addWeek()->setTime(16, 0),
            'is_featured' => false,
            'is_published' => true,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => false,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_featured' => true,
        ]);
    }
}
