<?php

namespace Tests\Unit\Policies;

use App\Models\Donation;
use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DonationPolicyTest extends TestCase
{
    public function test_administrator_can_view_the_donor_ledger(): void
    {
        $administrator = User::factory()->administrator()->make();

        $this->assertTrue($administrator->can('viewAny', Donation::class));
    }

    public function test_administrator_can_view_a_donor_record(): void
    {
        $administrator = User::factory()->administrator()->make();
        $donation = Donation::factory()->make();

        $this->assertTrue($administrator->can('view', $donation));
    }

    public function test_administrator_can_record_a_donation(): void
    {
        $administrator = User::factory()->administrator()->make();

        $this->assertTrue($administrator->can('create', Donation::class));
    }

    public function test_administrator_can_remove_a_donor_record(): void
    {
        $administrator = User::factory()->administrator()->make();
        $donation = Donation::factory()->make();

        $this->assertTrue($administrator->can('delete', $donation));
    }

    #[DataProvider('staffAbilities')]
    public function test_staff_cannot_manage_donors(string $ability, bool $needsDonation): void
    {
        $staff = User::factory()->make();
        $arguments = $needsDonation
            ? Donation::factory()->make()
            : Donation::class;

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
            'delete' => ['delete', true],
        ];
    }
}
