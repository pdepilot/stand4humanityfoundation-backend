<?php

namespace Database\Factories;

use App\Enums\DonationCause;
use App\Enums\DonationCurrency;
use App\Enums\DonationFrequency;
use App\Enums\DonationPaymentMethod;
use App\Enums\DonationSource;
use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cause = fake()->randomElement(DonationCause::cases());

        return [
            'reference' => 'don-'.Str::lower(Str::random(12)),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '080'.fake()->numerify('########'),
            'location' => 'Owerri, Imo State',
            'description' => 'Donation toward '.$cause->label(),
            'amount' => fake()->numberBetween(1000, 250000),
            'currency' => DonationCurrency::Ngn,
            'cause' => $cause,
            'frequency' => DonationFrequency::OneTime,
            'payment_method' => DonationPaymentMethod::Paystack,
            'anonymous' => false,
            'source' => DonationSource::Public,
        ];
    }

    /**
     * Hide the donor's name on the public ledger.
     */
    public function anonymous(): static
    {
        return $this->state(fn (array $attributes): array => [
            'anonymous' => true,
        ]);
    }

    /**
     * Record a gift entered from the command desk.
     */
    public function fromDesk(): static
    {
        return $this->state(fn (array $attributes): array => [
            'source' => DonationSource::Desk,
        ]);
    }
}
