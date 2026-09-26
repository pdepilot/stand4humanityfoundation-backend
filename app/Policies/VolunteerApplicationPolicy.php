<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VolunteerApplication;

class VolunteerApplicationPolicy
{
    /**
     * Determine whether the user can view the volunteer desk.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can view a volunteer application.
     */
    public function view(User $user, VolunteerApplication $volunteerApplication): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can record a volunteer from the desk.
     */
    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can update a volunteer status.
     */
    public function update(User $user, VolunteerApplication $volunteerApplication): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can remove a volunteer application.
     */
    public function delete(User $user, VolunteerApplication $volunteerApplication): bool
    {
        return $user->isAdministrator();
    }
}
