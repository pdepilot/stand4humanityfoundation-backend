<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\Donation;
use App\Models\FoundationMember;
use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AuditReportControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.audit.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_audit_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.audit.index'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->post(route('portal.audit.store'), ['report' => 'cases'])
            ->assertForbidden();
    }

    public function test_administrator_sees_the_export_desk(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.audit.index'))
            ->assertOk()
            ->assertSee('Export reports')
            ->assertSee('Case reports')
            ->assertSee('Meeting attendance')
            ->assertSee('Donations');
    }

    public function test_empty_choice_returns_a_validation_error(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.audit.index'))
            ->post(route('portal.audit.store'), [])
            ->assertRedirect(route('portal.audit.index'))
            ->assertSessionHasErrors('report');
    }

    public function test_administrator_can_export_cases_and_donations(): void
    {
        $administrator = User::factory()->administrator()->create();
        $report = IncidentReport::factory()->create([
            'reference' => 'SCVF-2026-800001',
            'incident_title' => '=unsafe title',
        ]);
        $donation = Donation::factory()->create([
            'reference' => 'don-audit000001',
        ]);

        $cases = $this->actingAs($administrator)
            ->post(route('portal.audit.store'), ['report' => 'cases']);

        $cases->assertOk();
        $cases->assertDownload();
        $caseCsv = $cases->streamedContent();
        $this->assertStringContainsString('SCVF-2026-800001', $caseCsv);
        $this->assertStringContainsString("'=unsafe title", $caseCsv);
        $this->assertStringNotContainsString('don-audit000001', $caseCsv);

        $donations = $this->actingAs($administrator)
            ->post(route('portal.audit.store'), ['report' => 'donations']);

        $donations->assertOk();
        $this->assertStringContainsString('don-audit000001', $donations->streamedContent());
        $this->assertStringNotContainsString($report->reference, $donations->streamedContent());
        $this->assertNotNull($donation->id);
    }

    public function test_a_date_range_limits_the_member_export(): void
    {
        $administrator = User::factory()->administrator()->create();
        $earlier = FoundationMember::factory()->create([
            'member_number' => 'SFH-00011',
            'full_name' => 'Earlier Member',
        ]);
        $later = FoundationMember::factory()->create([
            'member_number' => 'SFH-00012',
            'full_name' => 'Later Member',
        ]);
        $earlier->forceFill(['created_at' => '2026-01-15 09:00:00'])->save();
        $later->forceFill(['created_at' => '2026-09-01 09:00:00'])->save();

        $response = $this->actingAs($administrator)
            ->post(route('portal.audit.store'), [
                'report' => 'members',
                'from' => '2026-08-01',
                'to' => '2026-09-30',
            ]);

        $response->assertOk();
        $csv = $response->streamedContent();
        $this->assertStringContainsString('SFH-00012', $csv);
        $this->assertStringNotContainsString('SFH-00011', $csv);
    }
}
