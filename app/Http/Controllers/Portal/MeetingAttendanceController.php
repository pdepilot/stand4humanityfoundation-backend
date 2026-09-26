<?php

namespace App\Http\Controllers\Portal;

use App\Actions\SuspendAbsentMembersAction;
use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\MarkMeetingAttendanceRequest;
use App\Models\FoundationMember;
use App\Models\MeetingAttendance;
use App\Support\SaturdayMeeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MeetingAttendanceController extends Controller
{
    /**
     * Display the Saturday attendance roll.
     */
    public function index(Request $request, SuspendAbsentMembersAction $suspendAbsentMembers): View
    {
        Gate::authorize('viewAny', MeetingAttendance::class);

        $suspendAbsentMembers->handle();

        $meetingOn = SaturdayMeeting::openMeetingDate();
        $members = FoundationMember::query()
            ->where('status', MembershipStatus::Active)
            ->orderBy('full_name')
            ->orderBy('id')
            ->get();

        $presentIds = $meetingOn === null
            ? []
            : MeetingAttendance::query()
                ->whereDate('meeting_on', $meetingOn->toDateString())
                ->pluck('foundation_member_id')
                ->all();

        return view('portal.members.attendance', [
            'user' => $request->user(),
            'members' => $members,
            'presentIds' => $presentIds,
            'isOpen' => SaturdayMeeting::isOpen(),
            'meetingLabel' => $meetingOn?->format('l j M Y'),
        ]);
    }

    /**
     * Mark a member present while the Saturday desk is open.
     */
    public function store(MarkMeetingAttendanceRequest $request): RedirectResponse
    {
        $meetingOn = SaturdayMeeting::openMeetingDate();

        if ($meetingOn === null) {
            return redirect()
                ->route('portal.members.attendance')
                ->withErrors(['attendance' => 'Attendance marking is closed. It opens on Saturdays from 9:00 to 10:00.']);
        }

        $member = FoundationMember::query()->findOrFail($request->memberId());

        if ($member->status !== MembershipStatus::Active) {
            return redirect()
                ->route('portal.members.attendance')
                ->withErrors(['attendance' => 'Only an active member can be marked present.']);
        }

        $attendance = MeetingAttendance::query()->firstOrCreate(
            [
                'foundation_member_id' => $member->id,
                'meeting_on' => $meetingOn->toDateString(),
            ],
            [
                'marked_by' => $request->user()?->id,
                'marked_at' => now(),
            ],
        );

        $status = $attendance->wasRecentlyCreated
            ? $member->full_name.' is marked present.'
            : $member->full_name.' was already marked present.';

        return redirect()
            ->route('portal.members.attendance')
            ->with('status', $status);
    }
}
