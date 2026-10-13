<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Models\NewsletterSubscriber;
use App\Models\User;

class NewsletterSubscriberPolicy
{
    /**
     * Determine whether the user can view the subscriber ledger.
     */
    public function viewAny(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Subscribers);
    }

    /**
     * Determine whether the user can view a subscriber.
     */
    public function view(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->canOpenPortal(PortalPermission::Subscribers);
    }

    /**
     * Determine whether the user can record a subscriber from the desk.
     */
    public function create(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Subscribers);
    }

    /**
     * Determine whether the user can update a subscriber status.
     */
    public function update(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->canOpenPortal(PortalPermission::Subscribers);
    }

    /**
     * Determine whether the user can remove a subscriber.
     */
    public function delete(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->canOpenPortal(PortalPermission::Subscribers);
    }
}
