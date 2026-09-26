<?php

namespace App\Models;

use App\Enums\IncidentRelationship;
use App\Enums\IncidentSource;
use App\Enums\IncidentStatus;
use App\Enums\IncidentType;
use App\Enums\VictimGender;
use Database\Factories\IncidentReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'reference',
    'status',
    'source',
    'full_name',
    'phone',
    'email',
    'alt_contact',
    'relationship',
    'incident_type',
    'incident_title',
    'incident_date',
    'incident_time',
    'state',
    'lga',
    'community',
    'full_address',
    'description',
    'victim_name',
    'victim_age',
    'victim_gender',
    'victim_phone',
    'victim_last_location',
    'suspect_name',
    'suspect_alias',
    'suspect_description',
    'suspect_address',
    'suspect_vehicle',
    'suspect_notes',
    'incident_address',
    'latitude',
    'longitude',
    'consented_at',
])]
class IncidentReport extends Model
{
    /** @use HasFactory<IncidentReportFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => IncidentStatus::Submitted->value,
        'source' => IncidentSource::Public->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => IncidentStatus::class,
            'source' => IncidentSource::class,
            'relationship' => IncidentRelationship::class,
            'incident_type' => IncidentType::class,
            'incident_date' => 'date',
            'victim_age' => 'integer',
            'victim_gender' => VictimGender::class,
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'consented_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<IncidentReportFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(IncidentReportFile::class);
    }

    public function reporterName(): string
    {
        if ($this->relationship->isAnonymous() || $this->full_name === null || $this->full_name === '') {
            return 'Anonymous Reporter';
        }

        return $this->full_name;
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function coordinatesLabel(): ?string
    {
        if (! $this->hasCoordinates()) {
            return null;
        }

        return $this->latitude.', '.$this->longitude;
    }

    public function mapEmbedUrl(): ?string
    {
        if (! $this->hasCoordinates()) {
            return null;
        }

        return 'https://www.google.com/maps?q='.rawurlencode((string) $this->latitude.','.$this->longitude).'&z=15&output=embed';
    }

    /**
     * @return array{reference: string, status: string, incidentType: string, submittedAt: string|null}
     */
    public function toPublicArray(): array
    {
        return [
            'reference' => $this->reference,
            'status' => $this->status->label(),
            'incidentType' => $this->incident_type->value,
            'submittedAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
