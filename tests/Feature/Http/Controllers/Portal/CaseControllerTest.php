<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\IncidentSource;
use App\Enums\IncidentStatus;
use App\Models\IncidentReport;
use App\Models\IncidentReportFile;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CaseControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.cases.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_case_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.cases.index'))
            ->assertForbidden();
    }

    public function test_administrator_sees_the_empty_case_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.cases.index'))
            ->assertOk()
            ->assertSee('Case ledger')
            ->assertSee('No cases on the desk yet.')
            ->assertSee('Record a case');
    }

    public function test_administrator_sees_public_and_desk_cases_on_the_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();
        IncidentReport::factory()->create([
            'full_name' => 'Ada Obi',
            'incident_title' => 'Public filing',
            'reference' => 'SCVF-2026-100001',
        ]);
        IncidentReport::factory()->fromDesk()->create([
            'full_name' => 'Desk Officer',
            'incident_title' => 'Desk filing',
            'reference' => 'SCVF-2026-100002',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.cases.index'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('Desk Officer')
            ->assertSee('SCVF-2026-100001')
            ->assertSee('SCVF-2026-100002')
            ->assertSee('Report Live')
            ->assertSee('Command desk')
            ->assertSee('2 cases');
    }

    public function test_administrator_sees_anonymous_reporter_label(): void
    {
        $administrator = User::factory()->administrator()->create();
        IncidentReport::factory()->anonymous()->create([
            'incident_title' => 'Anonymous filing',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.cases.index'))
            ->assertOk()
            ->assertSee('Anonymous Reporter')
            ->assertSee('Anonymous filing');
    }

    public function test_case_ledger_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        IncidentReport::factory()->create([
            'full_name' => "<script>alert('xss')</script>",
            'incident_title' => '<img src=x onerror=alert(1)>',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.cases.index'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_administrator_can_view_the_record_form(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.cases.create'))
            ->assertOk()
            ->assertSee('Record a case')
            ->assertSee('Case details')
            ->assertSee('Anonymous Reporter')
            ->assertSee('GPS latitude')
            ->assertSee('GPS longitude');
    }

    public function test_staff_cannot_submit_a_desk_case(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->post(route('portal.cases.store'), $this->validPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('incident_reports', 0);
    }

    public function test_empty_desk_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.cases.create'))
            ->post(route('portal.cases.store'), [])
            ->assertRedirect(route('portal.cases.create'))
            ->assertSessionHasErrors([
                'full_name' => 'Enter the reporter name, or choose Anonymous Reporter.',
                'phone' => 'Enter a phone number, or choose Anonymous Reporter.',
                'relationship' => 'Select the relationship to the incident.',
                'incident_type' => 'Select the type of incident.',
                'description' => 'Describe the incident.',
            ]);

        $this->assertDatabaseCount('incident_reports', 0);
    }

    public function test_administrator_can_record_a_desk_case(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.cases.create'))
            ->post(route('portal.cases.store'), $this->validPayload())
            ->assertRedirectToRoute('portal.cases.show', IncidentReport::query()->first())
            ->assertSessionHas('status', 'The case is on the desk.');

        $report = IncidentReport::query()->first();

        $this->assertNotNull($report);
        $this->assertSame(IncidentSource::Desk, $report->source);
        $this->assertSame(IncidentStatus::Submitted, $report->status);
        $this->assertSame('Ngozi Eze', $report->full_name);
        $this->assertSame('ngozi@example.com', $report->email);
        $this->assertMatchesRegularExpression('/^SCVF-\d{4}-\d{6}$/', $report->reference);
    }

    public function test_desk_status_reference_and_source_keys_are_ignored(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.cases.store'), $this->validPayload([
                'status' => 'closed',
                'reference' => 'SCVF-1999-000001',
                'source' => 'public',
            ]))
            ->assertRedirect();

        $report = IncidentReport::query()->first();

        $this->assertNotNull($report);
        $this->assertSame(IncidentSource::Desk, $report->source);
        $this->assertSame(IncidentStatus::Submitted, $report->status);
        $this->assertNotSame('SCVF-1999-000001', $report->reference);
    }

    public function test_administrator_can_view_a_case_record(): void
    {
        $administrator = User::factory()->administrator()->create();
        $report = IncidentReport::factory()->create([
            'full_name' => 'Ada Obi',
            'phone' => '08012345678',
            'email' => 'ada@example.com',
            'description' => 'A child was seen in unsafe conditions and needs urgent protection from the foundation response team.',
            'reference' => 'SCVF-2026-333333',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.cases.show', $report))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('ada@example.com')
            ->assertSee('08012345678')
            ->assertSee('SCVF-2026-333333')
            ->assertSee('A child was seen in unsafe conditions')
            ->assertSee('Update status')
            ->assertSee('Remove record');
    }

    public function test_administrator_sees_the_captured_incident_location(): void
    {
        $administrator = User::factory()->administrator()->create();
        $report = IncidentReport::factory()->create([
            'incident_address' => 'GPS 5.499027, 7.036175',
            'latitude' => '5.499027',
            'longitude' => '7.036175',
            'reference' => 'SCVF-2026-555555',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.cases.show', $report))
            ->assertOk()
            ->assertSee('Incident location')
            ->assertSee('GPS 5.499027, 7.036175')
            ->assertSee('5.4990270, 7.0361750')
            ->assertSee('google.com/maps?q=', false)
            ->assertSee('Captured incident location');
    }

    public function test_case_record_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        $report = IncidentReport::factory()->create([
            'full_name' => "<script>alert('xss')</script>",
            'description' => '<svg onload=alert(1)> A child was seen in unsafe conditions and needs urgent protection from the foundation response team.',
            'incident_title' => '<img src=x onerror=alert(1)>',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.cases.show', $report))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertDontSee('<svg onload=alert(1)>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_staff_cannot_view_a_case_record(): void
    {
        $staff = User::factory()->create();
        $report = IncidentReport::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.cases.show', $report))
            ->assertForbidden();
    }

    public function test_administrator_can_update_case_status(): void
    {
        $administrator = User::factory()->administrator()->create();
        $report = IncidentReport::factory()->create([
            'status' => IncidentStatus::Submitted,
            'reference' => 'SCVF-2026-444444',
        ]);

        $this->actingAs($administrator)
            ->from(route('portal.cases.show', $report))
            ->put(route('portal.cases.update', $report), [
                'status' => IncidentStatus::UnderReview->value,
            ])
            ->assertRedirectToRoute('portal.cases.show', $report)
            ->assertSessionHas('status', 'The case status was updated.');

        $this->assertSame(IncidentStatus::UnderReview, $report->fresh()->status);

        $this->getJson(route('reports.api.show', $report->fresh()))
            ->assertOk()
            ->assertJsonPath('report.status', 'Under Review')
            ->assertJsonPath('report.reference', 'SCVF-2026-444444');
    }

    public function test_staff_cannot_update_case_status(): void
    {
        $staff = User::factory()->create();
        $report = IncidentReport::factory()->create();

        $this->actingAs($staff)
            ->put(route('portal.cases.update', $report), [
                'status' => IncidentStatus::Closed->value,
            ])
            ->assertForbidden();

        $this->assertSame(IncidentStatus::Submitted, $report->fresh()->status);
    }

    public function test_administrator_can_download_private_evidence(): void
    {
        Storage::fake('local');

        $administrator = User::factory()->administrator()->create();
        $report = IncidentReport::factory()->create();
        $path = 'incident-reports/'.$report->id.'/scene.jpg';
        Storage::disk('local')->put($path, 'evidence-bytes');
        $file = IncidentReportFile::factory()->create([
            'incident_report_id' => $report->id,
            'path' => $path,
            'original_name' => 'scene.jpg',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.cases.files.download', [$report, $file]))
            ->assertOk()
            ->assertHeader('content-disposition');
    }

    public function test_staff_cannot_download_private_evidence(): void
    {
        Storage::fake('local');

        $staff = User::factory()->create();
        $report = IncidentReport::factory()->create();
        $path = 'incident-reports/'.$report->id.'/scene.jpg';
        Storage::disk('local')->put($path, 'evidence-bytes');
        $file = IncidentReportFile::factory()->create([
            'incident_report_id' => $report->id,
            'path' => $path,
            'original_name' => 'scene.jpg',
        ]);

        $this->actingAs($staff)
            ->get(route('portal.cases.files.download', [$report, $file]))
            ->assertForbidden();
    }

    public function test_evidence_from_another_case_is_not_downloadable(): void
    {
        Storage::fake('local');

        $administrator = User::factory()->administrator()->create();
        $report = IncidentReport::factory()->create();
        $other = IncidentReport::factory()->create();
        $path = 'incident-reports/'.$other->id.'/scene.jpg';
        Storage::disk('local')->put($path, 'evidence-bytes');
        $file = IncidentReportFile::factory()->create([
            'incident_report_id' => $other->id,
            'path' => $path,
            'original_name' => 'scene.jpg',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.cases.files.download', [$report, $file]))
            ->assertNotFound();
    }

    public function test_administrator_can_remove_a_case_and_its_files(): void
    {
        $disk = Storage::fake('local');

        $administrator = User::factory()->administrator()->create();
        $report = IncidentReport::factory()->create();
        $path = 'incident-reports/'.$report->id.'/scene.jpg';
        $disk->put($path, 'evidence-bytes');
        IncidentReportFile::factory()->create([
            'incident_report_id' => $report->id,
            'path' => $path,
        ]);

        $this->actingAs($administrator)
            ->from(route('portal.cases.show', $report))
            ->delete(route('portal.cases.destroy', $report))
            ->assertRedirectToRoute('portal.cases.index')
            ->assertSessionHas('status', 'The case was removed from the desk.');

        $this->assertModelMissing($report);
        $this->assertDatabaseCount('incident_report_files', 0);
        $disk->assertMissing($path);
    }

    public function test_staff_cannot_remove_a_case(): void
    {
        $staff = User::factory()->create();
        $report = IncidentReport::factory()->create();

        $this->actingAs($staff)
            ->delete(route('portal.cases.destroy', $report))
            ->assertForbidden();

        $this->assertModelExists($report);
    }

    public function test_administrator_can_store_desk_evidence(): void
    {
        $disk = Storage::fake('local');

        $administrator = User::factory()->administrator()->create();
        $file = UploadedFile::fake()->image('desk.jpg', 80, 80);

        $this->actingAs($administrator)
            ->post(route('portal.cases.store'), $this->validPayload([
                'evidence' => [$file],
            ]))
            ->assertRedirect();

        $stored = IncidentReportFile::query()->first();

        $this->assertNotNull($stored);
        $this->assertSame('desk.jpg', $stored->original_name);
        $disk->assertExists($stored->path);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Ngozi Eze',
            'phone' => '08098765432',
            'email' => 'ngozi@example.com',
            'relationship' => 'Community Member',
            'incident_type' => 'Kidnapping',
            'incident_title' => 'Missing teenager in Owerri',
            'incident_date' => '2026-09-20',
            'state' => 'Imo',
            'description' => 'A teenager has been missing since yesterday evening and the family asked the foundation to record the case.',
            'confirm_accurate' => '1',
            'confirm_false' => '1',
            'consent_processing' => '1',
        ], $overrides);
    }
}
