<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.dashboard'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_authenticated_users_see_the_dashboard(): void
    {
        $user = User::factory()->administrator()->create([
            'name' => 'Portal Administrator',
        ]);

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('Command Desk')
            ->assertSee('Portal Administrator')
            ->assertSee('Administrator')
            ->assertSee('The stand is open.')
            ->assertDontSee('Open the wing')
            ->assertSee('data-count="0"', false);
    }

    public function test_dashboard_donor_count_matches_the_ledger(): void
    {
        $user = User::factory()->administrator()->create();
        Donation::factory()->count(2)->create();

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertSee('data-count="2"', false)
            ->assertSee(route('portal.donors.index', absolute: false), false);
    }

    public function test_dashboard_case_count_matches_the_ledger(): void
    {
        $user = User::factory()->administrator()->create();
        IncidentReport::factory()->count(2)->create();

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertSee('data-count="2"', false)
            ->assertSee(route('portal.cases.index', absolute: false), false);
    }

    public function test_dashboard_contact_count_matches_the_ledger(): void
    {
        $user = User::factory()->administrator()->create();
        ContactMessage::factory()->count(2)->create();

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertSee('data-count="2"', false)
            ->assertSee(route('portal.contacts.index', absolute: false), false);
    }

    public function test_dashboard_escapes_the_authenticated_user_name(): void
    {
        $user = User::factory()->create([
            'name' => "<script>alert('xss')</script>",
        ]);

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);
    }
}
