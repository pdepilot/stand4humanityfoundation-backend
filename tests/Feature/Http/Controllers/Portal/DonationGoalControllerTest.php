<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\DonationCause;
use App\Models\Donation;
use App\Models\DonationSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DonationGoalControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->put(route('portal.donation-goal.update'), $this->validGoals())
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_cannot_set_the_donation_goals(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->put(route('portal.donation-goal.update'), $this->validGoals([
                'child-protection' => 50000,
            ]))
            ->assertForbidden();

        $this->assertDatabaseMissing('donation_settings', [
            'child_protection_goal' => 50000,
        ]);
    }

    public function test_empty_goal_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.dashboard'))
            ->put(route('portal.donation-goal.update'), [])
            ->assertRedirect(route('portal.dashboard'))
            ->assertSessionHasErrors([
                'goals' => 'Enter a goal for each programme.',
                'spent_amount' => 'Enter the amount spent so far.',
            ]);
    }

    public function test_negative_programme_goal_is_rejected(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.dashboard'))
            ->put(route('portal.donation-goal.update'), $this->validGoals([
                'rescue' => -10,
            ]))
            ->assertRedirect(route('portal.dashboard'))
            ->assertSessionHasErrors([
                'goals.rescue' => 'Enter a goal of at least 0 for Emergency Rescue & Intervention.',
            ]);
    }

    public function test_administrator_can_set_each_programme_goal(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.dashboard'))
            ->put(route('portal.donation-goal.update'), array_merge($this->validGoals([
                'child-protection' => 250000,
                'rescue' => 180000,
                'victim-support' => 90000,
            ]), [
                'role' => 'staff',
                'goal_amount' => 1,
            ]))
            ->assertRedirectToRoute('portal.dashboard')
            ->assertSessionHas('status', 'The donation goals were updated.');

        $setting = DonationSetting::current();

        $this->assertSame('250000.00', $setting->child_protection_goal);
        $this->assertSame('180000.00', $setting->rescue_goal);
        $this->assertSame('90000.00', $setting->victim_support_goal);
        $this->assertSame('15000.00', $setting->spent_amount);
        $this->assertTrue($administrator->fresh()->isAdministrator());
    }

    public function test_dashboard_shows_each_programme_goal_to_administrators(): void
    {
        $administrator = User::factory()->administrator()->create();
        DonationSetting::factory()->create([
            'child_protection_goal' => 80000,
            'rescue_goal' => 50000,
            'victim_support_goal' => 40000,
            'spent_amount' => 5000,
        ]);
        Donation::factory()->create([
            'amount' => 20000,
            'currency' => 'NGN',
            'cause' => DonationCause::ChildProtection,
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('Donation goals')
            ->assertSee('Child Protection & Rescue')
            ->assertSee('Emergency Rescue & Intervention')
            ->assertSee('Victim Support & Advocacy')
            ->assertSee('Amount spent so far')
            ->assertSee('Save goals')
            ->assertSee('80,000')
            ->assertSee('20,000')
            ->assertSee('5,000')
            ->assertSee('25% raised');
    }

    public function test_staff_see_the_goals_but_not_the_form(): void
    {
        $staff = User::factory()->create();
        DonationSetting::factory()->create([
            'child_protection_goal' => 80000,
            'rescue_goal' => 50000,
            'victim_support_goal' => 40000,
        ]);

        $this->actingAs($staff)
            ->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('Donation goals')
            ->assertSee('80,000')
            ->assertDontSee('Save goals');
    }

    public function test_donate_page_shows_each_programme_goal(): void
    {
        DonationSetting::factory()->create([
            'child_protection_goal' => 120000,
            'rescue_goal' => 60000,
            'victim_support_goal' => 30000,
        ]);
        Donation::factory()->create([
            'amount' => 30000,
            'currency' => 'NGN',
            'cause' => DonationCause::ChildProtection,
        ]);

        $this->get(route('donate'))
            ->assertOk()
            ->assertSee('data-donation-cause="child-protection"', false)
            ->assertSee('data-donation-cause="rescue"', false)
            ->assertSee('data-donation-cause="victim-support"', false)
            ->assertSee('120,000')
            ->assertSee('60,000')
            ->assertSee('30,000')
            ->assertSee('25%')
            ->assertDontSee('10,000');
    }

    public function test_donations_ledger_shows_the_amount_spent(): void
    {
        DonationSetting::factory()->create([
            'child_protection_goal' => 100000,
            'rescue_goal' => 0,
            'victim_support_goal' => 0,
            'spent_amount' => 12000,
        ]);
        Donation::factory()->create([
            'amount' => 40000,
            'currency' => 'NGN',
            'cause' => DonationCause::ChildProtection,
        ]);

        $this->get(route('donations'))
            ->assertOk()
            ->assertSee('Amount Spent So Far')
            ->assertSee('12,000')
            ->assertSee('28,000');
    }

    /**
     * @param  array<string, int|float>  $overrides
     * @return array{goals: array<string, int|float>, spent_amount: int|float}
     */
    private function validGoals(array $overrides = []): array
    {
        return [
            'goals' => array_merge([
                'child-protection' => 10000,
                'rescue' => 10000,
                'victim-support' => 10000,
            ], $overrides),
            'spent_amount' => 15000,
        ];
    }
}
