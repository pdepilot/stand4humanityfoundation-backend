<?php

namespace Tests\Unit\Policies;

use App\Models\DonationSetting;
use App\Models\User;
use Tests\TestCase;

class DonationSettingPolicyTest extends TestCase
{
    public function test_administrator_can_set_the_donation_goal(): void
    {
        $administrator = User::factory()->administrator()->make();
        $setting = DonationSetting::factory()->make();

        $this->assertTrue($administrator->can('update', $setting));
    }

    public function test_staff_cannot_set_the_donation_goal(): void
    {
        $staff = User::factory()->make();
        $setting = DonationSetting::factory()->make();

        $this->assertFalse($staff->can('update', $setting));
    }
}
