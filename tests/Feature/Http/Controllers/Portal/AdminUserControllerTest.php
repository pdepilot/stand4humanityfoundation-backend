<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.admins.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_administrators_cannot_manage_other_admins(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.admins.index'))
            ->assertForbidden();
    }

    public function test_super_administrator_can_add_an_administrator(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();

        $this->actingAs($superAdministrator)
            ->post(route('portal.admins.store'), [
                'name' => 'Ada Obi',
                'email' => 'ada@example.com',
                'password' => 'portal-pass',
                'password_confirmation' => 'portal-pass',
            ])
            ->assertRedirectToRoute('portal.admins.index')
            ->assertSessionHas('status', 'The administrator was added.');

        $admin = User::query()->where('email', 'ada@example.com')->first();

        $this->assertNotNull($admin);
        $this->assertSame(UserRole::Administrator, $admin->role);
        $this->assertTrue($admin->isAdministrator());
        $this->assertFalse($admin->isSuperAdministrator());
    }

    public function test_empty_admin_payload_returns_validation_errors(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();

        $this->actingAs($superAdministrator)
            ->from(route('portal.admins.index'))
            ->post(route('portal.admins.store'), [])
            ->assertRedirect(route('portal.admins.index'))
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_super_administrator_can_suspend_and_restore_an_administrator(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();
        $administrator = User::factory()->administrator()->create([
            'email' => 'desk@example.com',
        ]);

        $this->actingAs($superAdministrator)
            ->put(route('portal.admins.update', $administrator))
            ->assertRedirectToRoute('portal.admins.index')
            ->assertSessionHas('status', 'The administrator was suspended.');

        $this->assertTrue($administrator->fresh()->isSuspended());

        $this->post(route('portal.logout'));

        $this->post(route('portal.login.store'), [
            'email' => 'desk@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors(['email' => 'This account is suspended.']);

        $this->assertGuest();

        $this->actingAs($superAdministrator)
            ->put(route('portal.admins.update', $administrator))
            ->assertSessionHas('status', 'The administrator was restored.');

        $this->assertFalse($administrator->fresh()->isSuspended());
    }

    public function test_a_suspended_session_is_signed_out(): void
    {
        $administrator = User::factory()->administrator()->suspended()->create();

        $this->actingAs($administrator)
            ->get(route('portal.dashboard'))
            ->assertRedirect(route('portal.login'))
            ->assertSessionHasErrors(['email' => 'This account is suspended.']);

        $this->assertGuest();
    }

    public function test_super_administrator_can_delete_an_administrator(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($superAdministrator)
            ->delete(route('portal.admins.destroy', $administrator))
            ->assertRedirectToRoute('portal.admins.index')
            ->assertSessionHas('status', 'The administrator was removed.');

        $this->assertModelMissing($administrator);
        $this->assertModelExists($superAdministrator);
    }

    public function test_super_administrator_cannot_delete_themselves(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();

        $this->actingAs($superAdministrator)
            ->delete(route('portal.admins.destroy', $superAdministrator))
            ->assertForbidden();

        $this->assertModelExists($superAdministrator);
    }
}
