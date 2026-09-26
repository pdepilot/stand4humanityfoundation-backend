<?php

namespace App\Actions;

use App\Enums\MembershipStatus;
use App\Mail\MemberSuspended;
use App\Models\FoundationMember;
use App\Models\MeetingAttendance;
use App\Support\SaturdayMeeting;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SuspendAbsentMembersAction
{
    /**
     * Suspend active members who missed the last three closed Saturday meetings.
     */
    public function handle(): int
    {
        $meetings = SaturdayMeeting::recentClosedMeetings(12);

        if ($meetings === []) {
            return 0;
        }

        $oldestMeeting = $meetings[array_key_last($meetings)];

        $present = MeetingAttendance::query()
            ->whereDate('meeting_on', '>=', $oldestMeeting)
            ->get(['foundation_member_id', 'meeting_on'])
            ->groupBy('foundation_member_id');

        $suspended = 0;

        FoundationMember::query()
            ->where('status', MembershipStatus::Active)
            ->orderBy('id')
            ->each(function (FoundationMember $member) use ($meetings, $present, &$suspended): void {
                $joinedOn = $member->joined_on?->toDateString();

                if (! is_string($joinedOn)) {
                    return;
                }

                $eligible = collect($meetings)
                    ->filter(fn (string $date): bool => $date >= $joinedOn)
                    ->take(3)
                    ->values();

                if ($eligible->count() < 3) {
                    return;
                }

                $attendedDates = $present->get($member->id, collect())
                    ->map(fn (MeetingAttendance $attendance): string => $attendance->meeting_on->toDateString());

                if ($eligible->intersect($attendedDates)->isNotEmpty()) {
                    return;
                }

                $member->update(['status' => MembershipStatus::Suspended]);
                $this->notify($member);
                $suspended++;
            });

        return $suspended;
    }

    private function notify(FoundationMember $member): void
    {
        if (! is_string($member->email) || trim($member->email) === '') {
            return;
        }

        try {
            Mail::to($member->email)->send(new MemberSuspended($member));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
