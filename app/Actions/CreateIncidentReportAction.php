<?php

namespace App\Actions;

use App\Enums\IncidentRelationship;
use App\Enums\IncidentSource;
use App\Enums\IncidentStatus;
use App\Enums\IncidentType;
use App\Enums\VictimGender;
use App\Events\FrontendActivityRecorded;
use App\Mail\ReportReceived;
use App\Models\IncidentReport;
use App\Models\IncidentReportFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CreateIncidentReportAction
{
    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, UploadedFile>  $files
     */
    public function handle(array $data, IncidentSource $source = IncidentSource::Public, array $files = []): IncidentReport
    {
        $report = DB::transaction(function () use ($data, $source, $files): IncidentReport {
            $report = IncidentReport::query()->create([
                'reference' => $this->uniqueReference(),
                'status' => IncidentStatus::Submitted,
                'source' => $source,
                'full_name' => $data['full_name'] ?? null,
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'alt_contact' => $data['alt_contact'] ?? null,
                'relationship' => IncidentRelationship::from($data['relationship']),
                'incident_type' => IncidentType::from($data['incident_type']),
                'incident_title' => $data['incident_title'],
                'incident_date' => $data['incident_date'],
                'incident_time' => $data['incident_time'] ?? null,
                'state' => $data['state'],
                'lga' => $data['lga'] ?? null,
                'community' => $data['community'] ?? null,
                'full_address' => $data['full_address'] ?? null,
                'description' => $data['description'],
                'victim_name' => $data['victim_name'] ?? null,
                'victim_age' => $data['victim_age'] ?? null,
                'victim_gender' => isset($data['victim_gender']) ? VictimGender::from($data['victim_gender']) : null,
                'victim_phone' => $data['victim_phone'] ?? null,
                'victim_last_location' => $data['victim_last_location'] ?? null,
                'suspect_name' => $data['suspect_name'] ?? null,
                'suspect_alias' => $data['suspect_alias'] ?? null,
                'suspect_description' => $data['suspect_description'] ?? null,
                'suspect_address' => $data['suspect_address'] ?? null,
                'suspect_vehicle' => $data['suspect_vehicle'] ?? null,
                'suspect_notes' => $data['suspect_notes'] ?? null,
                'incident_address' => $data['incident_address'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'consented_at' => now(),
            ]);

            foreach ($files as $file) {
                $path = $file->store('incident-reports/'.$report->id, 'local');

                if (! is_string($path) || $path === '') {
                    continue;
                }

                IncidentReportFile::query()->create([
                    'incident_report_id' => $report->id,
                    'disk' => 'local',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }

            return $report->load('files');
        });

        if ($source === IncidentSource::Public) {
            FrontendActivityRecorded::dispatch(
                'report',
                'Report Live filing',
                $report->reference.' · '.$report->incident_type->label(),
                route('portal.cases.show', $report, false),
                $report->reference,
            );
        }

        $this->sendReceipt($report);

        return $report;
    }

    /**
     * Tell the reporter their reference code and how to follow the case.
     */
    private function sendReceipt(IncidentReport $report): void
    {
        if (! is_string($report->email) || trim($report->email) === '') {
            return;
        }

        try {
            Mail::to($report->email)->send(new ReportReceived($report));
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function uniqueReference(): string
    {
        $year = (int) now()->format('Y');

        do {
            $reference = sprintf('SCVF-%d-%06d', $year, random_int(0, 999999));
        } while (IncidentReport::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
