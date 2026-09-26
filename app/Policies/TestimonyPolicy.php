<?php

namespace App\Policies;

use App\Models\Testimony;
use App\Models\User;

class TestimonyPolicy
{
    /**
     * Determine whether the user can view the testimony desk.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can view a testimony.
     */
    public function view(User $user, Testimony $testimony): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can publish a testimony from the desk.
     */
    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can approve or decline a testimony.
     */
    public function update(User $user, Testimony $testimony): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can remove a testimony.
     */
    public function delete(User $user, Testimony $testimony): bool
    {
        return $user->isAdministrator();
    }
}
