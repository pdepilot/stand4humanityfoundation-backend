<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\VolunteerApplication;
use App\Policies\VolunteerApplicationPolicy;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class VolunteerApplicationPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_only_administrators_may_manage_volunteer_applications(): void
    {
        $policy = new VolunteerApplicationPolicy;
        $administrator = User::factory()->administrator()->create();
        $staff = User::factory()->create();
        $application = VolunteerApplication::factory()->create();

        $this->assertTrue($policy->viewAny($administrator));
        $this->assertTrue($policy->view($administrator, $application));
        $this->assertTrue($policy->create($administrator));
        $this->assertTrue($policy->update($administrator, $application));
        $this->assertTrue($policy->delete($administrator, $application));

        $this->assertFalse($policy->viewAny($staff));
        $this->assertFalse($policy->view($staff, $application));
        $this->assertFalse($policy->create($staff));
        $this->assertFalse($policy->update($staff, $application));
        $this->assertFalse($policy->delete($staff, $application));
    }
}
