<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\MembershipStatus;
use App\Mail\MemberSuspended;
use App\Models\FoundationMember;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class MemberControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.members.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_members_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.members.index'))
            ->assertForbidden();
    }

    public function test_the_member_form_offers_upload_and_camera(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.members.create'))
            ->assertOk()
            ->assertSee('Upload photo')
            ->assertSee('Use camera')
            ->assertSee('FCT - Abuja')
            ->assertSee('Lagos')
            ->assertSee('js/portal/member-photo.js', false);
    }

    public function test_administrator_sees_the_empty_roll(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.members.index'))
            ->assertOk()
            ->assertSee('Membership roll')
            ->assertSee('No members on the roll yet.')
            ->assertSee('Record a member');
    }

    public function test_administrator_can_record_a_member_with_a_photo(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.members.store'), $this->validPayload())
            ->assertRedirect();

        $member = FoundationMember::query()->first();

        $this->assertNotNull($member);
        $this->assertSame('SFH-00001', $member->member_number);
        $this->assertSame('Ada Obi', $member->full_name);
        $this->assertSame('08031234567', $member->phone);
        $this->assertSame(MembershipStatus::Active, $member->status);
        $disk->assertExists($member->photo);

        $this->actingAs($administrator)
            ->get(route('portal.members.show', $member))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('SFH-00001')
            ->assertSee('12 Wetheral Road');
    }

    public function test_empty_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.members.create'))
            ->post(route('portal.members.store'), [])
            ->assertRedirect(route('portal.members.create'))
            ->assertSessionHasErrors([
                'full_name',
                'photo',
                'gender',
                'date_of_birth',
                'phone',
                'address',
                'city',
                'joined_on',
                'status',
            ]);

        $this->assertDatabaseCount('foundation_members', 0);
    }

    public function test_administrator_can_look_up_a_member_by_name_or_number(): void
    {
        $administrator = User::factory()->administrator()->create();
        FoundationMember::factory()->create([
            'full_name' => 'Ada Obi',
            'member_number' => 'SFH-00041',
        ]);
        FoundationMember::factory()->create([
            'full_name' => 'Chinedu Eze',
            'member_number' => 'SFH-00042',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.members.index', ['q' => 'Ada']))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertDontSee('Chinedu Eze');

        $this->actingAs($administrator)
            ->get(route('portal.members.index', ['q' => 'SFH-00042']))
            ->assertOk()
            ->assertSee('Chinedu Eze')
            ->assertDontSee('Ada Obi');
    }

    public function test_administrator_can_edit_a_member_and_replace_the_photo(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->create([
            'full_name' => 'Ada Obi',
            'member_number' => 'SFH-00007',
            'photo' => 'members/original.jpg',
        ]);
        $disk->put('members/original.jpg', 'portrait');

        $this->actingAs($administrator)
            ->put(route('portal.members.update', $member), [
                ...$this->validPayload(),
                'full_name' => 'Ada Obi Nwosu',
                'status' => MembershipStatus::Suspended->value,
                'photo' => UploadedFile::fake()->image('updated.jpg'),
            ])
            ->assertRedirect(route('portal.members.show', $member));

        $member->refresh();

        $this->assertSame('Ada Obi Nwosu', $member->full_name);
        $this->assertSame('SFH-00007', $member->member_number);
        $this->assertSame(MembershipStatus::Suspended, $member->status);
        $this->assertNotSame('members/original.jpg', $member->photo);
        $disk->assertMissing('members/original.jpg');
        $disk->assertExists($member->photo);
    }

    public function test_administrator_can_delete_a_member(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->create([
            'full_name' => 'Ada Obi',
            'member_number' => 'SFH-00009',
            'photo' => 'members/ada.jpg',
        ]);
        $disk->put('members/ada.jpg', 'portrait');

        $this->actingAs($administrator)
            ->delete(route('portal.members.destroy', $member))
            ->assertRedirect(route('portal.members.index'))
            ->assertSessionHas('status', 'Ada Obi was removed from the roll.');

        $this->assertModelMissing($member);
        $disk->assertMissing('members/ada.jpg');
    }

    public function test_staff_cannot_delete_a_member(): void
    {
        $staff = User::factory()->create();
        $member = FoundationMember::factory()->create();

        $this->actingAs($staff)
            ->delete(route('portal.members.destroy', $member))
            ->assertForbidden();

        $this->assertModelExists($member);
    }

    public function test_administrator_can_suspend_a_member_and_email_them(): void
    {
        Mail::fake();
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->create([
            'full_name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'member_number' => 'SFH-00010',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.members.show', $member))
            ->assertOk()
            ->assertSee('Suspend member')
            ->assertSee("return confirm('Suspend this member?');", false);

        $this->actingAs($administrator)
            ->put(route('portal.members.suspend', $member))
            ->assertRedirect(route('portal.members.show', $member))
            ->assertSessionHas('status', 'Ada Obi was suspended.');

        $this->assertSame(MembershipStatus::Suspended, $member->refresh()->status);
        Mail::assertSent(MemberSuspended::class, function (MemberSuspended $mail) use ($member): bool {
            return $mail->hasTo('ada@example.com')
                && $mail->member->is($member)
                && $mail->forAbsence === false;
        });

        (new MemberSuspended($member, forAbsence: false))
            ->assertSeeInHtml('The foundation desk has suspended your membership')
            ->assertDontSeeInHtml('three Saturday meetings');
    }

    public function test_administrator_can_restore_a_suspended_member(): void
    {
        Mail::fake();
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->suspended()->create([
            'full_name' => 'Ada Obi',
            'email' => 'ada@example.com',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.members.show', $member))
            ->assertOk()
            ->assertSee('Restore member')
            ->assertSee("return confirm('Restore this member?');", false);

        $this->actingAs($administrator)
            ->put(route('portal.members.suspend', $member))
            ->assertRedirect(route('portal.members.show', $member))
            ->assertSessionHas('status', 'Ada Obi was restored to the roll.');

        $this->assertSame(MembershipStatus::Active, $member->refresh()->status);
        Mail::assertNothingSent();
    }

    public function test_a_member_without_an_email_is_still_suspended(): void
    {
        Mail::fake();
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->create([
            'email' => null,
        ]);

        $this->actingAs($administrator)
            ->put(route('portal.members.suspend', $member))
            ->assertRedirect(route('portal.members.show', $member));

        $this->assertSame(MembershipStatus::Suspended, $member->refresh()->status);
        Mail::assertNothingSent();
    }

    public function test_a_mail_failure_does_not_undo_a_desk_suspension(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('mailbox unavailable'));
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->create([
            'email' => 'ada@example.com',
        ]);

        $this->actingAs($administrator)
            ->put(route('portal.members.suspend', $member))
            ->assertRedirect(route('portal.members.show', $member));

        $this->assertSame(MembershipStatus::Suspended, $member->refresh()->status);
    }

    public function test_staff_cannot_suspend_a_member(): void
    {
        $staff = User::factory()->create();
        $member = FoundationMember::factory()->create();

        $this->actingAs($staff)
            ->put(route('portal.members.suspend', $member))
            ->assertForbidden();

        $this->assertSame(MembershipStatus::Active, $member->refresh()->status);
    }

    public function test_roll_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        FoundationMember::factory()->create([
            'full_name' => "<script>alert('xss')</script>",
            'member_number' => 'SFH-00008',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.members.index'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(): array
    {
        return [
            'full_name' => 'Ada Obi',
            'photo' => UploadedFile::fake()->image('ada.jpg'),
            'gender' => 'female',
            'date_of_birth' => '1990-04-12',
            'phone' => '08031234567',
            'email' => 'ada@example.com',
            'address' => '12 Wetheral Road',
            'city' => 'Owerri',
            'state' => 'Imo',
            'occupation' => 'Teacher',
            'joined_on' => '2024-01-15',
            'status' => 'active',
            'emergency_contact_name' => 'Ngozi Obi',
            'emergency_contact_phone' => '08039876543',
            'notes' => 'Leads the Saturday outreach.',
        ];
    }
}
