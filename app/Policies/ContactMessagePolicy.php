<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Models\ContactMessage;
use App\Models\User;

class ContactMessagePolicy
{
    /**
     * Determine whether the user can view the contact ledger.
     */
    public function viewAny(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Contacts);
    }

    /**
     * Determine whether the user can view a contact message.
     */
    public function view(User $user, ContactMessage $contactMessage): bool
    {
        return $user->canOpenPortal(PortalPermission::Contacts);
    }

    /**
     * Determine whether the user can record a contact from the desk.
     */
    public function create(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Contacts);
    }

    /**
     * Determine whether the user can update a contact status.
     */
    public function update(User $user, ContactMessage $contactMessage): bool
    {
        return $user->canOpenPortal(PortalPermission::Contacts);
    }

    /**
     * Determine whether the user can remove a contact message.
     */
    public function delete(User $user, ContactMessage $contactMessage): bool
    {
        return $user->canOpenPortal(PortalPermission::Contacts);
    }
}
