<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    public function test_administrator_can_update_their_own_login_details(): void
    {
        $administrator = User::factory()->administrator()->make();

        $this->assertTrue($administrator->can('updateLoginDetails', $administrator));
    }

    public function test_staff_cannot_update_login_details(): void
    {
        $staff = User::factory()->make();

        $this->assertFalse($staff->can('updateLoginDetails', $staff));
    }

    public function test_administrator_cannot_update_another_users_login_details(): void
    {
        $administrator = User::factory()->administrator()->make(['id' => 1]);
        $other = User::factory()->administrator()->make(['id' => 2]);

        $this->assertFalse($administrator->can('updateLoginDetails', $other));
    }
}
