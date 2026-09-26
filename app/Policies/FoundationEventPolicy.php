<?php

namespace App\Policies;

use App\Models\FoundationEvent;
use App\Models\User;

class FoundationEventPolicy
{
    /**
     * Determine whether the user can view the events desk.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can view an event.
     */
    public function view(User $user, FoundationEvent $foundationEvent): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can schedule an event.
     */
    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can update an event.
     */
    public function update(User $user, FoundationEvent $foundationEvent): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can remove an event.
     */
    public function delete(User $user, FoundationEvent $foundationEvent): bool
    {
        return $user->isAdministrator();
    }
}
