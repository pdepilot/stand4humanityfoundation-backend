<?php

namespace App\Policies;

use App\Models\FoundationMember;
use App\Models\User;

class FoundationMemberPolicy
{
    /**
     * Determine whether the user can view the members desk.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can view a member.
     */
    public function view(User $user, FoundationMember $foundationMember): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can record a member.
     */
    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can update a member.
     */
    public function update(User $user, FoundationMember $foundationMember): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can remove a member.
     */
    public function delete(User $user, FoundationMember $foundationMember): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can suspend or restore a member.
     */
    public function suspend(User $user, FoundationMember $foundationMember): bool
    {
        return $user->isAdministrator();
    }
}
