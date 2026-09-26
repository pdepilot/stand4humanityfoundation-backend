<?php

namespace Tests\Unit\Policies;

use App\Models\ContactMessage;
use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactMessagePolicyTest extends TestCase
{
    public function test_administrator_can_view_the_contact_ledger(): void
    {
        $administrator = User::factory()->administrator()->make();

        $this->assertTrue($administrator->can('viewAny', ContactMessage::class));
    }

    public function test_administrator_can_view_a_contact_message(): void
    {
        $administrator = User::factory()->administrator()->make();
        $message = ContactMessage::factory()->make();

        $this->assertTrue($administrator->can('view', $message));
    }

    public function test_administrator_can_record_a_contact_message(): void
    {
        $administrator = User::factory()->administrator()->make();

        $this->assertTrue($administrator->can('create', ContactMessage::class));
    }

    public function test_administrator_can_update_a_contact_message(): void
    {
        $administrator = User::factory()->administrator()->make();
        $message = ContactMessage::factory()->make();

        $this->assertTrue($administrator->can('update', $message));
    }

    public function test_administrator_can_remove_a_contact_message(): void
    {
        $administrator = User::factory()->administrator()->make();
        $message = ContactMessage::factory()->make();

        $this->assertTrue($administrator->can('delete', $message));
    }

    #[DataProvider('staffAbilities')]
    public function test_staff_cannot_manage_contacts(string $ability, bool $needsMessage): void
    {
        $staff = User::factory()->make();
        $arguments = $needsMessage
            ? ContactMessage::factory()->make()
            : ContactMessage::class;

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
