<?php

namespace App\Policies;

use App\Models\AdminRole;
use App\Models\User;

class AdminRolePolicy
{
    /**
     * Determine whether the user can view administrator roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdministrator();
    }

    /**
     * Determine whether the user can view an administrator role.
     */
    public function view(User $user, AdminRole $adminRole): bool
    {
        return $user->isSuperAdministrator();
    }

    /**
     * Determine whether the user can create an administrator role.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdministrator();
    }

    /**
     * Determine whether the user can change an administrator role.
     */
    public function update(User $user, AdminRole $adminRole): bool
    {
        return $user->isSuperAdministrator();
    }

    /**
     * Determine whether the user can remove an administrator role.
     */
    public function delete(User $user, AdminRole $adminRole): bool
    {
        return $user->isSuperAdministrator();
    }
}
