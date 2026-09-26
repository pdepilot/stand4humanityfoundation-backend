<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\User;
use App\Support\PortalLoginVisual;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthenticatedSessionControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_login_page_renders_for_guests(): void
    {
        $this->get(route('portal.login'))
            ->assertSee('Staff portal')
            ->assertSee('Welcome back.')
            ->assertSee(asset('images/portal/stand2.jpg'), false);
    }

    public function test_authenticated_users_are_redirected_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('portal.login'))
            ->assertRedirect(route('portal.dashboard'));
    }

    public function test_empty_login_payload_returns_validation_errors(): void
    {
        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [])
            ->assertRedirect(route('portal.login'))
            ->assertSessionHasErrors([
                'email' => 'Enter your email and password.',
                'password' => 'Enter your email and password.',
            ]);

        $this->assertGuest();
    }

    public function test_invalid_email_format_is_rejected(): void
    {
        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [
                'email' => 'not-an-email',
                'password' => 'secret',
            ])
            ->assertRedirect(route('portal.login'))
            ->assertSessionHasErrors([
                'email' => 'Enter a valid email address.',
            ]);

        $this->assertGuest();
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'staff@example.com',
        ]);

        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [
                'email' => 'staff@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('portal.login'))
            ->assertSessionHasErrors([
                'email' => 'Those details were not recognised.',
            ]);

        $this->assertGuest();
    }

    public function test_valid_credentials_authenticate_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'staff@example.com',
        ]);

        $this->post(route('portal.login.store'), [
            'email' => 'staff@example.com',
            'password' => 'password',
        ])
            ->assertRedirect(route('portal.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_remember_me_sets_the_remember_cookie(): void
    {
        $user = User::factory()->create([
            'email' => 'staff@example.com',
        ]);

        $this->post(route('portal.login.store'), [
            'email' => 'staff@example.com',
            'password' => 'password',
            'remember' => '1',
        ])
            ->assertRedirect(route('portal.dashboard'))
            ->assertCookie(Auth::guard('web')->getRecallerName());

        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_ends_the_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('portal.logout'))
            ->assertRedirect(route('portal.login', ['from' => 'logout']));

        $this->assertGuest();
    }

    public function test_logout_shows_a_different_login_image_than_before_sign_in(): void
    {
        $this->get(route('portal.login'))
            ->assertSee(asset('images/portal/stand2.jpg'), false)
            ->assertCookie(PortalLoginVisual::COOKIE, '0');

        $user = User::factory()->create();

        $this->withCookie(PortalLoginVisual::COOKIE, '0')
            ->actingAs($user)
            ->post(route('portal.logout'))
            ->assertRedirect(route('portal.login', ['from' => 'logout']))
            ->assertCookie(PortalLoginVisual::COOKIE, '1');

        $this->withCookie(PortalLoginVisual::COOKIE, '1')
            ->get(route('portal.login'))
            ->assertSee(asset('images/portal/stand4.jpg'), false)
            ->assertDontSee(asset('images/portal/stand2.jpg'), false);
    }

    public function test_successive_logouts_keep_rotating_the_login_image(): void
    {
        $user = User::factory()->create();

        $this->withCookie(PortalLoginVisual::COOKIE, '1')
            ->actingAs($user)
            ->post(route('portal.logout'))
            ->assertCookie(PortalLoginVisual::COOKIE, '2');

        $this->withCookie(PortalLoginVisual::COOKIE, '2')
            ->get(route('portal.login'))
            ->assertSee(asset('images/portal/stand5.jpg'), false)
            ->assertDontSee(asset('images/portal/stand4.jpg'), false);
    }

    public function test_the_portal_signs_out_after_15_minutes_without_activity(): void
    {
        $user = User::factory()->create();

        $this->travelTo(Carbon::parse('2026-09-24 12:00:00'));

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertOk();

        $this->travel(14)->minutes();

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertOk();

        $this->travel(15)->minutes();

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertRedirect(route('portal.login'))
            ->assertSessionHasErrors(['email' => 'You were signed out after 15 minutes of inactivity.']);

        $this->assertGuest();
    }

    public function test_three_failed_logins_ban_the_ip_for_24_hours(): void
    {
        $this->travelTo(Carbon::parse('2026-09-24 12:00:00'));
        User::factory()->create([
            'email' => 'staff@example.com',
        ]);

        for ($attempt = 0; $attempt < 2; $attempt++) {
            $this->from(route('portal.login'))
                ->post(route('portal.login.store'), [
                    'email' => 'staff@example.com',
                    'password' => 'wrong-password',
                ])
                ->assertSessionHasErrors(['email' => 'Those details were not recognised.']);
        }

        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [
                'email' => 'staff@example.com',
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors(['email' => 'This device is blocked for 24 hours after three failed sign-in attempts.']);

        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [
                'email' => 'staff@example.com',
                'password' => 'password',
            ])
            ->assertSessionHasErrors(['email' => 'This device is blocked for 24 hours after three failed sign-in attempts.']);

        $this->assertGuest();
    }

    public function test_a_failure_after_the_24_hour_ban_blocks_the_ip_for_72_hours_then_permanently(): void
    {
        $this->travelTo(Carbon::parse('2026-09-24 12:00:00'));
        User::factory()->create([
            'email' => 'staff@example.com',
        ]);

        $this->failLogin(3);

        $this->travel(25)->hours();

        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [
                'email' => 'staff@example.com',
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors(['email' => 'This device is blocked for 72 hours.']);

        $this->travel(73)->hours();

        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [
                'email' => 'staff@example.com',
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors(['email' => 'This device is blocked from the portal.']);

        $this->travel(365)->days();

        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [
                'email' => 'staff@example.com',
                'password' => 'password',
            ])
            ->assertSessionHasErrors(['email' => 'This device is blocked from the portal.']);

        $this->assertGuest();
    }

    private function failLogin(int $times): void
    {
        for ($attempt = 0; $attempt < $times; $attempt++) {
            $this->from(route('portal.login'))
                ->post(route('portal.login.store'), [
                    'email' => 'staff@example.com',
                    'password' => 'wrong-password',
                ]);
        }
    }

    public function test_login_form_escapes_previous_email_input(): void
    {
        $dangerousEmail = "<script>alert('xss')</script>";

        $this->from(route('portal.login'))
            ->post(route('portal.login.store'), [
                'email' => $dangerousEmail,
                'password' => 'secret',
            ])
            ->assertRedirect(route('portal.login'));

        $this->get(route('portal.login'))
            ->assertDontSee($dangerousEmail, false)
            ->assertSee('&lt;script&gt;', false);
    }
}
