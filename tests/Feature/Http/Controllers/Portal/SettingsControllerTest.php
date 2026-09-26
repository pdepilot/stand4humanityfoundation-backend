<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.settings.edit'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_settings_page(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.settings.edit'))
            ->assertForbidden();
    }

    public function test_administrator_can_view_the_settings_page(): void
    {
        $administrator = User::factory()->administrator()->create([
            'email' => 'desk@example.com',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.settings.edit'))
            ->assertSee('Login details')
            ->assertSee('desk@example.com')
            ->assertSee('Settings');
    }

    public function test_settings_page_escapes_the_administrator_name(): void
    {
        $administrator = User::factory()->administrator()->create([
            'name' => "<script>alert('xss')</script>",
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.settings.edit'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_empty_settings_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.settings.edit'))
            ->put(route('portal.settings.update'), [])
            ->assertRedirect(route('portal.settings.edit'))
            ->assertSessionHasErrors([
                'current_password' => 'Enter your current password.',
                'email' => 'Enter the new login email.',
            ]);
    }

    public function test_invalid_email_format_is_rejected(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.settings.edit'))
            ->put(route('portal.settings.update'), [
                'current_password' => 'password',
                'email' => 'not-an-email',
            ])
            ->assertRedirect(route('portal.settings.edit'))
            ->assertSessionHasErrors([
                'email' => 'Enter a valid email address.',
            ]);
    }

    public function test_duplicate_email_is_rejected(): void
    {
        User::factory()->create([
            'email' => 'taken@example.com',
        ]);
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.settings.edit'))
            ->put(route('portal.settings.update'), [
                'current_password' => 'password',
                'email' => 'taken@example.com',
            ])
            ->assertRedirect(route('portal.settings.edit'))
            ->assertSessionHasErrors([
                'email' => 'That email is already in use.',
            ]);
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.settings.edit'))
            ->put(route('portal.settings.update'), [
                'current_password' => 'wrong-password',
                'email' => $administrator->email,
            ])
            ->assertRedirect(route('portal.settings.edit'))
            ->assertSessionHasErrors([
                'current_password' => 'That current password is not correct.',
            ]);
    }

    public function test_password_confirmation_must_match(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.settings.edit'))
            ->put(route('portal.settings.update'), [
                'current_password' => 'password',
                'email' => $administrator->email,
                'password' => 'new-secret',
                'password_confirmation' => 'different-secret',
            ])
            ->assertRedirect(route('portal.settings.edit'))
            ->assertSessionHasErrors([
                'password' => 'The new password confirmation does not match.',
            ]);
    }

    public function test_short_password_is_rejected(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.settings.edit'))
            ->put(route('portal.settings.update'), [
                'current_password' => 'password',
                'email' => $administrator->email,
                'password' => 'short',
                'password_confirmation' => 'short',
            ])
            ->assertRedirect(route('portal.settings.edit'))
            ->assertSessionHasErrors([
                'password' => 'Use at least 8 characters for the new password.',
            ]);
    }

    public function test_administrator_can_change_email_and_password(): void
    {
        $administrator = User::factory()->administrator()->create([
            'email' => 'desk@example.com',
        ]);

        $this->actingAs($administrator)
            ->from(route('portal.settings.edit'))
            ->put(route('portal.settings.update'), [
                'current_password' => 'password',
                'email' => 'new-desk@example.com',
                'password' => 'new-secret',
                'password_confirmation' => 'new-secret',
                'role' => 'staff',
            ])
            ->assertRedirectToRoute('portal.settings.edit')
            ->assertSessionHas('status', 'Login details were updated.');

        $administrator->refresh();

        $this->assertSame('new-desk@example.com', $administrator->email);
        $this->assertTrue(Hash::check('new-secret', $administrator->password));
        $this->assertTrue($administrator->isAdministrator());
        $this->assertAuthenticatedAs($administrator);
    }

    public function test_administrator_can_change_email_without_changing_password(): void
    {
        $administrator = User::factory()->administrator()->create([
            'email' => 'desk@example.com',
        ]);

        $this->actingAs($administrator)
            ->put(route('portal.settings.update'), [
                'current_password' => 'password',
                'email' => 'kept-password@example.com',
            ])
            ->assertRedirectToRoute('portal.settings.edit');

        $administrator->refresh();

        $this->assertSame('kept-password@example.com', $administrator->email);
        $this->assertTrue(Hash::check('password', $administrator->password));
    }

    public function test_staff_cannot_submit_login_detail_changes(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->put(route('portal.settings.update'), [
                'current_password' => 'password',
                'email' => 'staff-new@example.com',
                'password' => 'new-secret',
                'password_confirmation' => 'new-secret',
            ])
            ->assertForbidden();

        $staff->refresh();

        $this->assertNotSame('staff-new@example.com', $staff->email);
        $this->assertTrue(Hash::check('password', $staff->password));
    }
}
