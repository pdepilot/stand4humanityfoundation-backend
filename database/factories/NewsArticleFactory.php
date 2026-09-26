<?php

namespace Database\Factories;

use App\Models\NewsArticle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<NewsArticle>
 */
class NewsArticleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Child protection note '.fake()->unique()->numberBetween(1, 9999);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'excerpt' => 'A short programme note from the foundation desk in Owerri.',
            'body' => "## What this note covers\n\nStand For Humanity Foundation protects children and supports people in need across Owerri, Imo State.\n\nWe publish original programme notes so families know how to ask for help.\n\n> We stand with those who cannot stand alone.\n\n*This page explains our programme. It is not a report of a specific case.*",
            'image' => 'event-3.jpg',
            'image_alt' => 'Foundation programme activity in Owerri',
            'author' => 'Foundation desk',
            'reading_minutes' => 4,
            'tags' => ['Child Protection', 'Owerri'],
            'search_keywords' => 'child protection rescue owerri',
            'is_published' => true,
            'is_popular' => false,
            'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => false,
        ]);
    }

    public function popular(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_popular' => true,
        ]);
    }
}
