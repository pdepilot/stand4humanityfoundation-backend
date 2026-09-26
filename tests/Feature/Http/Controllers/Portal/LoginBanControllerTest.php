<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\PortalLoginBan;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LoginBanControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_staff_cannot_open_or_lift_a_login_block(): void
    {
        $staff = User::factory()->create();
        $ban = PortalLoginBan::query()->create([
            'ip_address' => '203.0.113.10',
            'failures' => 0,
            'stage' => 3,
        ]);

        $this->actingAs($staff)
            ->get(route('portal.login-bans.index'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->delete(route('portal.login-bans.destroy', $ban))
            ->assertForbidden();

        $this->assertModelExists($ban);
    }

    public function test_administrator_can_unban_a_device_so_it_can_sign_in(): void
    {
        $administrator = User::factory()->administrator()->create();
        $staff = User::factory()->create([
            'email' => 'staff@example.com',
        ]);
        $ban = PortalLoginBan::query()->create([
            'ip_address' => '127.0.0.1',
            'failures' => 0,
            'stage' => 3,
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.login-bans.index'))
            ->assertOk()
            ->assertSee('127.0.0.1')
            ->assertSee('Permanent')
            ->assertSee('Unban');

        $this->actingAs($administrator)
            ->delete(route('portal.login-bans.destroy', $ban))
            ->assertRedirect(route('portal.login-bans.index'))
            ->assertSessionHas('status', '127.0.0.1 can sign in again.');

        $this->assertModelMissing($ban);

        $this->post(route('portal.logout'));

        $this->post(route('portal.login.store'), [
            'email' => $staff->email,
            'password' => 'password',
        ])->assertRedirect(route('portal.dashboard'));

        $this->assertAuthenticatedAs($staff);
    }
}
