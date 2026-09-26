<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\IncidentSource;
use App\Enums\IncidentStatus;
use App\Mail\ReportReceived;
use App\Models\IncidentReport;
use App\Models\IncidentReportFile;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IncidentReportControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_report_page_includes_csrf_token_and_capture_script(): void
    {
        $this->get(route('report'))
            ->assertSee('name="csrf-token"', false)
            ->assertSee('Charitize/js/report-live.js', false)
            ->assertSee('id="incidentReportForm"', false)
            ->assertSee('/api/reports', false);
    }

    public function test_empty_payload_returns_422_with_required_field_messages(): void
    {
        $this->postJson(route('reports.api.store'), [])
            ->assertUnprocessable()
            ->assertJsonPath('errors.full_name.0', 'Enter the reporter name, or choose Anonymous Reporter.')
            ->assertJsonPath('errors.phone.0', 'Enter a phone number, or choose Anonymous Reporter.')
            ->assertJsonPath('errors.relationship.0', 'Select the relationship to the incident.')
            ->assertJsonPath('errors.incident_type.0', 'Select the type of incident.')
            ->assertJsonPath('errors.incident_title.0', 'Enter an incident title.')
            ->assertJsonPath('errors.incident_date.0', 'Enter the date of the incident.')
            ->assertJsonPath('errors.state.0', 'Select the state.')
            ->assertJsonPath('errors.description.0', 'Describe the incident.')
            ->assertJsonPath('errors.confirm_accurate.0', 'Confirm that the information is accurate.')
            ->assertJsonPath('errors.confirm_false.0', 'Confirm that you understand false reporting causes harm.')
            ->assertJsonPath('errors.consent_processing.0', 'Consent is required before this report can be received.');

        $this->assertDatabaseCount('incident_reports', 0);
    }

    public function test_returns_422_when_the_description_is_too_short(): void
    {
        $this->postJson(route('reports.api.store'), $this->validPayload([
            'description' => 'Too short to explain the incident.',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.description.0', 'Describe the incident in at least 50 characters.');

        $this->assertDatabaseCount('incident_reports', 0);
    }

    public function test_honeypot_payload_is_rejected_without_saving(): void
    {
        $this->postJson(route('reports.api.store'), $this->validPayload([
            'website' => 'https://spam.test',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.website.0', 'Unable to accept this report.');

        $this->assertDatabaseCount('incident_reports', 0);
    }

    public function test_valid_payload_creates_a_public_report_and_returns_201(): void
    {
        $this->freezeTime();

        $response = $this->postJson(route('reports.api.store'), $this->validPayload());

        $response
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('report.status', 'Submitted')
            ->assertJsonPath('report.incidentType', 'Child Abuse')
            ->assertJsonMissingPath('report.full_name')
            ->assertJsonMissingPath('report.phone')
            ->assertJsonMissingPath('report.description')
            ->assertJsonMissingPath('report.source')
            ->assertDontSee('Ada Obi')
            ->assertDontSee('08012345678');

        $report = IncidentReport::query()->first();

        $this->assertNotNull($report);
        $this->assertMatchesRegularExpression('/^SCVF-\d{4}-\d{6}$/', $report->reference);
        $this->assertSame($report->reference, $response->json('report.reference'));
        $this->assertSame(IncidentSource::Public, $report->source);
        $this->assertSame(IncidentStatus::Submitted, $report->status);
        $this->assertSame('Ada Obi', $report->full_name);
        $this->assertSame('08012345678', $report->phone);
        $this->assertNotNull($report->consented_at);
        $this->assertSame($report->created_at?->toIso8601String(), $response->json('report.submittedAt'));
    }

    public function test_a_report_with_an_email_sends_the_reference_code(): void
    {
        Mail::fake();

        $this->postJson(route('reports.api.store'), $this->validPayload())
            ->assertCreated();

        $report = IncidentReport::query()->first();

        $this->assertNotNull($report);

        Mail::assertSent(ReportReceived::class, function (ReportReceived $mail) use ($report): bool {
            return $mail->hasTo('ada@example.com')
                && $mail->report->is($report)
                && str_contains($mail->envelope()->subject, $report->reference);
        });
    }

    public function test_a_report_without_an_email_does_not_send_mail(): void
    {
        Mail::fake();

        $this->postJson(route('reports.api.store'), $this->validPayload([
            'relationship' => 'Anonymous Reporter',
            'full_name' => '',
            'phone' => '',
            'email' => '',
        ]))->assertCreated();

        Mail::assertNothingSent();
    }

    public function test_detected_location_is_stored_with_the_report(): void
    {
        $this->postJson(route('reports.api.store'), $this->validPayload([
            'incident_address' => 'GPS 5.499027, 7.036175',
            'latitude' => '5.499027',
            'longitude' => '7.036175',
        ]))
            ->assertCreated()
            ->assertJsonMissingPath('report.latitude')
            ->assertJsonMissingPath('report.longitude')
            ->assertJsonMissingPath('report.incident_address')
            ->assertDontSee('5.499027')
            ->assertDontSee('7.036175');

        $report = IncidentReport::query()->first();

        $this->assertNotNull($report);
        $this->assertSame('GPS 5.499027, 7.036175', $report->incident_address);
        $this->assertSame('5.4990270', $report->latitude);
        $this->assertSame('7.0361750', $report->longitude);
        $this->assertTrue($report->hasCoordinates());
        $this->assertSame('5.4990270, 7.0361750', $report->coordinatesLabel());
        $this->assertStringContainsString('5.4990270', (string) $report->mapEmbedUrl());
        $this->assertStringContainsString('7.0361750', (string) $report->mapEmbedUrl());
    }

    public function test_anonymous_reporter_can_omit_name_and_phone(): void
    {
        $this->postJson(route('reports.api.store'), $this->validPayload([
            'relationship' => 'Anonymous Reporter',
            'full_name' => '',
            'phone' => '',
            'email' => '',
        ]))
            ->assertCreated()
            ->assertJsonPath('report.status', 'Submitted')
            ->assertDontSee('Ada Obi');

        $report = IncidentReport::query()->first();

        $this->assertNotNull($report);
        $this->assertNull($report->full_name);
        $this->assertNull($report->phone);
        $this->assertSame('Anonymous Reporter', $report->reporterName());
    }

    public function test_unexpected_status_reference_and_source_keys_are_ignored(): void
    {
        $response = $this->postJson(route('reports.api.store'), $this->validPayload([
            'status' => 'closed',
            'reference' => 'SCVF-1999-000001',
            'source' => 'desk',
        ]));

        $response->assertCreated();

        $report = IncidentReport::query()->first();

        $this->assertNotNull($report);
        $this->assertSame(IncidentStatus::Submitted, $report->status);
        $this->assertSame(IncidentSource::Public, $report->source);
        $this->assertNotSame('SCVF-1999-000001', $report->reference);
    }

    public function test_evidence_files_are_stored_on_the_private_disk(): void
    {
        $disk = Storage::fake('local');

        $file = UploadedFile::fake()->image('scene.jpg', 120, 80);

        $this->post(route('reports.api.store'), $this->validPayload([
            'evidence' => [$file],
        ]), ['Accept' => 'application/json'])
            ->assertCreated();

        $stored = IncidentReportFile::query()->first();

        $this->assertNotNull($stored);
        $this->assertSame('local', $stored->disk);
        $this->assertSame('scene.jpg', $stored->original_name);
        $disk->assertExists($stored->path);
        $this->assertStringStartsWith('incident-reports/', $stored->path);
    }

    public function test_public_track_returns_only_the_safe_snapshot(): void
    {
        $report = IncidentReport::factory()->create([
            'reference' => 'SCVF-2026-111111',
            'full_name' => 'Secret Reporter',
            'phone' => '08011112222',
            'description' => 'Confidential case narrative that must not leak.',
            'latitude' => '5.499027',
            'longitude' => '7.036175',
            'status' => IncidentStatus::UnderReview,
        ]);

        $this->getJson(route('reports.api.show', $report))
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('report.reference', 'SCVF-2026-111111')
            ->assertJsonPath('report.status', 'Under Review')
            ->assertJsonPath('report.incidentType', 'Child Abuse')
            ->assertJsonMissingPath('report.full_name')
            ->assertJsonMissingPath('report.phone')
            ->assertJsonMissingPath('report.description')
            ->assertDontSee('Secret Reporter')
            ->assertDontSee('08011112222')
            ->assertDontSee('Confidential case narrative')
            ->assertDontSee('5.499027')
            ->assertDontSee('7.036175');
    }

    public function test_unknown_reference_returns_404_without_leaking_other_reports(): void
    {
        IncidentReport::factory()->create([
            'reference' => 'SCVF-2026-222222',
            'full_name' => 'Hidden Reporter',
            'phone' => '08099998888',
            'description' => 'Private narrative for another case.',
        ]);

        $this->getJson('/api/reports/SCVF-2026-000000')
            ->assertNotFound()
            ->assertDontSee('Hidden Reporter')
            ->assertDontSee('08099998888')
            ->assertDontSee('Private narrative for another case.');
    }

    public function test_invalid_reference_format_returns_404(): void
    {
        $this->getJson('/api/reports/not-a-ref')
            ->assertNotFound();
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Ada Obi',
            'phone' => '08012345678',
            'email' => 'ada@example.com',
            'relationship' => 'Witness',
            'incident_type' => 'Child Abuse',
            'incident_title' => 'Child in unsafe housing',
            'incident_date' => '2026-09-20',
            'state' => 'Imo',
            'description' => 'A child was seen in unsafe conditions and needs urgent protection from the foundation response team.',
            'confirm_accurate' => '1',
            'confirm_false' => '1',
            'consent_processing' => '1',
        ], $overrides);
    }
}
