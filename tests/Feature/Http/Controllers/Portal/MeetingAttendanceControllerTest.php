<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\MembershipStatus;
use App\Mail\MemberSuspended;
use App\Models\FoundationMember;
use App\Models\MeetingAttendance;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MeetingAttendanceControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.members.attendance'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_attendance_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.members.attendance'))
            ->assertForbidden();
    }

    public function test_administrator_can_mark_a_member_present_between_nine_and_ten(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-26 09:30:00', 'Africa/Lagos'));
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->create([
            'full_name' => 'Ada Obi',
            'joined_on' => '2026-09-26',
        ]);

        $this->actingAs($administrator)
            ->post(route('portal.members.attendance.store'), [
                'foundation_member_id' => $member->id,
            ])
            ->assertRedirect(route('portal.members.attendance'))
            ->assertSessionHas('status', 'Ada Obi is marked present.');

        $this->assertTrue(
            MeetingAttendance::query()
                ->where('foundation_member_id', $member->id)
                ->whereDate('meeting_on', '2026-09-26')
                ->where('marked_by', $administrator->id)
                ->exists()
        );
    }

    public function test_marking_closes_at_ten(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-26 10:00:00', 'Africa/Lagos'));
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->create([
            'joined_on' => '2026-09-26',
        ]);

        $this->actingAs($administrator)
            ->from(route('portal.members.attendance'))
            ->post(route('portal.members.attendance.store'), [
                'foundation_member_id' => $member->id,
            ])
            ->assertRedirect(route('portal.members.attendance'))
            ->assertSessionHasErrors('attendance');

        $this->assertDatabaseCount('meeting_attendances', 0);
    }

    public function test_marking_is_closed_before_nine_and_on_other_days(): void
    {
        $administrator = User::factory()->administrator()->create();
        $member = FoundationMember::factory()->create([
            'joined_on' => '2026-09-26',
        ]);

        Carbon::setTestNow(Carbon::parse('2026-09-26 08:59:00', 'Africa/Lagos'));

        $this->actingAs($administrator)
            ->post(route('portal.members.attendance.store'), [
                'foundation_member_id' => $member->id,
            ])
            ->assertSessionHasErrors('attendance');

        Carbon::setTestNow(Carbon::parse('2026-09-25 09:30:00', 'Africa/Lagos'));

        $this->actingAs($administrator)
            ->post(route('portal.members.attendance.store'), [
                'foundation_member_id' => $member->id,
            ])
            ->assertSessionHasErrors('attendance');

        $this->assertDatabaseCount('meeting_attendances', 0);
    }

    public function test_three_missed_saturdays_suspend_the_member_and_send_mail(): void
    {
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-09-26 10:05:00', 'Africa/Lagos'));
        $member = FoundationMember::factory()->create([
            'full_name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'joined_on' => '2026-08-01',
        ]);

        $this->artisan('meetings:suspend-absentees')->assertSuccessful();

        $member->refresh();

        $this->assertSame(MembershipStatus::Suspended, $member->status);
        Mail::assertSent(MemberSuspended::class, function (MemberSuspended $mail) use ($member): bool {
            return $mail->hasTo('ada@example.com')
                && $mail->member->is($member);
        });
    }

    public function test_a_present_mark_in_the_last_three_meetings_keeps_the_member_active(): void
    {
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-09-26 10:05:00', 'Africa/Lagos'));
        $member = FoundationMember::factory()->create([
            'email' => 'ada@example.com',
            'joined_on' => '2026-08-01',
        ]);
        MeetingAttendance::factory()->create([
            'foundation_member_id' => $member->id,
            'meeting_on' => '2026-09-19',
        ]);

        $this->artisan('meetings:suspend-absentees')->assertSuccessful();

        $this->assertSame(MembershipStatus::Active, $member->refresh()->status);
        Mail::assertNothingSent();
    }

    public function test_a_member_without_an_email_is_suspended_without_mail(): void
    {
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-09-26 10:05:00', 'Africa/Lagos'));
        $member = FoundationMember::factory()->create([
            'email' => null,
            'joined_on' => '2026-08-01',
        ]);

        $this->artisan('meetings:suspend-absentees')->assertSuccessful();

        $this->assertSame(MembershipStatus::Suspended, $member->refresh()->status);
        Mail::assertNothingSent();
    }
}
