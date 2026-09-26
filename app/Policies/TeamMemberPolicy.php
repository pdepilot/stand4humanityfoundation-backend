<?php

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;

class TeamMemberPolicy
{
    /**
     * Determine whether the user can view the team desk.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can view a team member.
     */
    public function view(User $user, TeamMember $teamMember): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can add a team member.
     */
    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can remove a team member.
     */
    public function delete(User $user, TeamMember $teamMember): bool
    {
        return $user->isAdministrator();
    }
}
