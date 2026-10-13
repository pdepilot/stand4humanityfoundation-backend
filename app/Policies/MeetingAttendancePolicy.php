<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Models\MeetingAttendance;
use App\Models\User;

class MeetingAttendancePolicy
{
    /**
     * Determine whether the user can view the attendance desk.
     */
    public function viewAny(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Attendance);
    }

    /**
     * Determine whether the user can mark a member present.
     */
    public function create(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Attendance);
    }

    /**
     * Determine whether the user can view an attendance mark.
     */
    public function view(User $user, MeetingAttendance $meetingAttendance): bool
    {
        return $user->canOpenPortal(PortalPermission::Attendance);
    }
}
