<?php

namespace Tests\Unit\Policies;

use App\Models\IncidentReport;
use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IncidentReportPolicyTest extends TestCase
{
    public function test_administrator_can_view_the_case_ledger(): void
    {
        $administrator = User::factory()->administrator()->make();

        $this->assertTrue($administrator->can('viewAny', IncidentReport::class));
    }

    public function test_administrator_can_view_a_case(): void
    {
        $administrator = User::factory()->administrator()->make();
        $report = IncidentReport::factory()->make();

        $this->assertTrue($administrator->can('view', $report));
    }

    public function test_administrator_can_record_a_case(): void
    {
        $administrator = User::factory()->administrator()->make();

        $this->assertTrue($administrator->can('create', IncidentReport::class));
    }

    public function test_administrator_can_update_a_case(): void
    {
        $administrator = User::factory()->administrator()->make();
        $report = IncidentReport::factory()->make();

        $this->assertTrue($administrator->can('update', $report));
    }

    public function test_administrator_can_remove_a_case(): void
    {
        $administrator = User::factory()->administrator()->make();
        $report = IncidentReport::factory()->make();

        $this->assertTrue($administrator->can('delete', $report));
    }

    #[DataProvider('staffAbilities')]
    public function test_staff_cannot_manage_cases(string $ability, bool $needsReport): void
    {
        $staff = User::factory()->make();
        $arguments = $needsReport
            ? IncidentReport::factory()->make()
            : IncidentReport::class;

        $this->assertFalse($staff->can($ability, $arguments));
    }

    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function staffAbilities(): array
    {
        return [
            'viewAny' => ['viewAny', false],
            'view' => ['view', true],
            'create' => ['create', false],
            'update' => ['update', true],
            'delete' => ['delete', true],
        ];
    }
}
