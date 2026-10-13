<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the actor may change the given user's login details.
     */
    public function updateLoginDetails(User $actor, User $user): bool
    {
        return $actor->canOpenPortal(PortalPermission::Settings) && $actor->is($user);
    }

    /**
     * Determine whether the actor may assign a role to an administrator.
     */
    public function assignRole(User $actor, User $user): bool
    {
        return $this->suspend($actor, $user);
    }

    /**
     * Determine whether the actor may open the admin roster.
     */
    public function viewAny(User $actor): bool
    {
        return $actor->isSuperAdministrator();
    }

    /**
     * Determine whether the actor may add an administrator.
     */
    public function create(User $actor): bool
    {
        return $actor->isSuperAdministrator();
    }

    /**
     * Determine whether the actor may suspend or restore an administrator.
     */
    public function suspend(User $actor, User $user): bool
    {
        return $actor->isSuperAdministrator()
            && $user->role === UserRole::Administrator
            && ! $actor->is($user);
    }

    /**
     * Determine whether the actor may remove an administrator.
     */
    public function delete(User $actor, User $user): bool
    {
        return $this->suspend($actor, $user);
    }
}
