<?php

namespace Database\Factories;

use App\Models\IncidentReport;
use App\Models\IncidentReportFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IncidentReportFile>
 */
class IncidentReportFileFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'incident_report_id' => IncidentReport::factory(),
            'disk' => 'local',
            'path' => 'incident-reports/example.jpg',
            'original_name' => 'evidence.jpg',
            'mime' => 'image/jpeg',
            'size' => 1024,
        ];
    }
}
