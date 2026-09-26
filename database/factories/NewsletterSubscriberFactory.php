<?php

namespace Database\Factories;

use App\Enums\NewsletterSource;
use App\Enums\NewsletterStatus;
use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<NewsletterSubscriber>
 */
class NewsletterSubscriberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => 'nws-'.Str::lower(bin2hex(random_bytes(6))),
            'status' => NewsletterStatus::Active,
            'source' => NewsletterSource::Public,
            'email' => fake()->unique()->safeEmail(),
            'country' => 'Nigeria',
            'country_code' => 'NG',
            'city' => 'Owerri',
            'ip_address' => '102.89.23.10',
            'unsubscribed_at' => null,
        ];
    }

    public function fromDesk(): static
    {
        return $this->state(fn (array $attributes): array => [
            'source' => NewsletterSource::Desk,
        ]);
    }

    public function unsubscribed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => NewsletterStatus::Unsubscribed,
            'unsubscribed_at' => now(),
        ]);
    }
}
