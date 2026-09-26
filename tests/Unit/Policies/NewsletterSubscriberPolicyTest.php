<?php

namespace Tests\Unit\Policies;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Policies\NewsletterSubscriberPolicy;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class NewsletterSubscriberPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_only_administrators_may_manage_newsletter_subscribers(): void
    {
        $policy = new NewsletterSubscriberPolicy;
        $administrator = User::factory()->administrator()->create();
        $staff = User::factory()->create();
        $subscriber = NewsletterSubscriber::factory()->create();

        $this->assertTrue($policy->viewAny($administrator));
        $this->assertTrue($policy->view($administrator, $subscriber));
        $this->assertTrue($policy->create($administrator));
        $this->assertTrue($policy->update($administrator, $subscriber));
        $this->assertTrue($policy->delete($administrator, $subscriber));

        $this->assertFalse($policy->viewAny($staff));
        $this->assertFalse($policy->view($staff, $subscriber));
        $this->assertFalse($policy->create($staff));
        $this->assertFalse($policy->update($staff, $subscriber));
        $this->assertFalse($policy->delete($staff, $subscriber));
    }
}
