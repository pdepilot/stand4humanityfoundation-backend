<?php

namespace Database\Factories;

use App\Enums\IncidentRelationship;
use App\Enums\IncidentSource;
use App\Enums\IncidentStatus;
use App\Enums\IncidentType;
use App\Models\IncidentReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IncidentReport>
 */
class IncidentReportFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = now()->year;

        return [
            'reference' => sprintf('SCVF-%d-%06d', $year, fake()->unique()->numberBetween(800000, 999999)),
            'status' => IncidentStatus::Submitted,
            'source' => IncidentSource::Public,
            'full_name' => fake()->name(),
            'phone' => '080'.fake()->numerify('########'),
            'email' => fake()->safeEmail(),
            'alt_contact' => null,
            'relationship' => IncidentRelationship::Witness,
            'incident_type' => IncidentType::ChildAbuse,
            'incident_title' => 'Child in unsafe housing',
            'incident_date' => now()->subDay()->toDateString(),
            'incident_time' => '14:30',
            'state' => 'Imo',
            'lga' => 'Owerri Municipal',
            'community' => 'Owerri',
            'full_address' => 'Owerri, Imo State',
            'description' => 'A child was seen in unsafe conditions and needs urgent protection from the foundation response team.',
            'consented_at' => now(),
        ];
    }

    public function anonymous(): static
    {
        return $this->state(fn (array $attributes): array => [
            'relationship' => IncidentRelationship::AnonymousReporter,
            'full_name' => null,
            'phone' => null,
            'email' => null,
        ]);
    }

    public function fromDesk(): static
    {
        return $this->state(fn (array $attributes): array => [
            'source' => IncidentSource::Desk,
        ]);
    }
}
