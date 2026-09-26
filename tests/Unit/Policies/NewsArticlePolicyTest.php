<?php

namespace Tests\Unit\Policies;

use App\Models\NewsArticle;
use App\Models\User;
use App\Policies\NewsArticlePolicy;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class NewsArticlePolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_only_administrators_may_manage_news_articles(): void
    {
        $policy = new NewsArticlePolicy;
        $administrator = User::factory()->administrator()->create();
        $staff = User::factory()->create();
        $article = NewsArticle::factory()->create();

        $this->assertTrue($policy->viewAny($administrator));
        $this->assertTrue($policy->view($administrator, $article));
        $this->assertTrue($policy->create($administrator));
        $this->assertTrue($policy->update($administrator, $article));
        $this->assertTrue($policy->delete($administrator, $article));

        $this->assertFalse($policy->viewAny($staff));
        $this->assertFalse($policy->view($staff, $article));
        $this->assertFalse($policy->create($staff));
        $this->assertFalse($policy->update($staff, $article));
        $this->assertFalse($policy->delete($staff, $article));
    }
}
