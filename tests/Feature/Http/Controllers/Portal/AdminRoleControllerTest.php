<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\PortalPermission;
use App\Models\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminRoleControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.roles.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_an_administrator_cannot_open_the_roles_desk(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.roles.index'))
            ->assertForbidden()
            ->assertSee(PortalPermission::DENIED_MESSAGE);
    }

    public function test_super_administrator_can_create_a_role(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();

        $this->actingAs($superAdministrator)
            ->post(route('portal.roles.store'), [
                'name' => 'News desk',
                'permissions' => [PortalPermission::News->value],
            ])
            ->assertRedirectToRoute('portal.roles.index')
            ->assertSessionHas('status', 'News desk was added.');

        $role = AdminRole::query()->where('name', 'News desk')->first();

        $this->assertNotNull($role);
        $this->assertSame([PortalPermission::News->value], $role->permissions);
    }

    public function test_an_empty_role_payload_returns_validation_errors(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();

        $this->actingAs($superAdministrator)
            ->from(route('portal.roles.index'))
            ->post(route('portal.roles.store'), [])
            ->assertRedirect(route('portal.roles.index'))
            ->assertSessionHasErrors([
                'name' => 'Enter a role name.',
                'permissions' => 'Choose at least one page.',
            ]);
    }

    public function test_super_administrator_can_assign_a_role_that_limits_pages(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();
        $administrator = User::factory()->administrator()->create();
        $role = AdminRole::factory()->create([
            'name' => 'News only',
            'permissions' => [PortalPermission::News->value],
        ]);

        $this->actingAs($superAdministrator)
            ->put(route('portal.admins.role', $administrator), [
                'admin_role_id' => $role->id,
            ])
            ->assertRedirectToRoute('portal.admins.index')
            ->assertSessionHas('status', 'The administrator role was updated.');

        $this->assertSame($role->id, (int) $administrator->refresh()->admin_role_id);

        $this->actingAs($administrator)
            ->get(route('portal.news.index'))
            ->assertOk();

        $this->actingAs($administrator)
            ->get(route('portal.donors.index'))
            ->assertForbidden()
            ->assertSee(PortalPermission::DENIED_MESSAGE);
    }

    public function test_clearing_a_role_restores_every_administrator_page(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();
        $role = AdminRole::factory()->create([
            'name' => 'News only',
            'permissions' => [PortalPermission::News->value],
        ]);
        $administrator = User::factory()->administrator()->create([
            'admin_role_id' => $role->id,
        ]);

        $this->actingAs($superAdministrator)
            ->put(route('portal.admins.role', $administrator), [
                'admin_role_id' => '',
            ])
            ->assertRedirectToRoute('portal.admins.index');

        $this->assertNull($administrator->refresh()->admin_role_id);

        $this->actingAs($administrator)
            ->get(route('portal.donors.index'))
            ->assertOk();
    }

    public function test_super_administrator_can_change_a_roles_pages(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();
        $role = AdminRole::factory()->create([
            'name' => 'News only',
            'permissions' => [PortalPermission::News->value],
        ]);
        $administrator = User::factory()->administrator()->create([
            'admin_role_id' => $role->id,
        ]);

        $this->actingAs($superAdministrator)
            ->put(route('portal.roles.update', $role), [
                'name' => 'Donors only',
                'permissions' => [PortalPermission::Donors->value],
            ])
            ->assertRedirectToRoute('portal.roles.index')
            ->assertSessionHas('status', 'The role was updated.');

        $this->actingAs($administrator)
            ->get(route('portal.news.index'))
            ->assertForbidden()
            ->assertSee(PortalPermission::DENIED_MESSAGE);

        $this->actingAs($administrator)
            ->get(route('portal.donors.index'))
            ->assertOk();
    }

    public function test_an_administrator_without_a_role_can_open_administrator_desks(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.news.index'))
            ->assertOk();

        $this->actingAs($administrator)
            ->get(route('portal.donors.index'))
            ->assertOk();
    }

    public function test_staff_are_told_they_are_not_permitted_to_open_an_administrator_page(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.news.index'))
            ->assertForbidden()
            ->assertSee(PortalPermission::DENIED_MESSAGE);
    }

    public function test_a_role_with_administrators_cannot_be_removed(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();
        $role = AdminRole::factory()->create(['name' => 'Cases desk']);
        User::factory()->administrator()->create([
            'admin_role_id' => $role->id,
        ]);

        $this->actingAs($superAdministrator)
            ->from(route('portal.roles.index'))
            ->delete(route('portal.roles.destroy', $role))
            ->assertRedirect(route('portal.roles.index'))
            ->assertSessionHasErrors([
                'role' => 'Move the administrators off this role before you remove it.',
            ]);

        $this->assertModelExists($role);
    }

    public function test_super_administrator_can_remove_an_unused_role(): void
    {
        $superAdministrator = User::factory()->superAdministrator()->create();
        $role = AdminRole::factory()->create(['name' => 'Mail desk']);

        $this->actingAs($superAdministrator)
            ->delete(route('portal.roles.destroy', $role))
            ->assertRedirectToRoute('portal.roles.index')
            ->assertSessionHas('status', 'Mail desk was removed.');

        $this->assertModelMissing($role);
    }
}
