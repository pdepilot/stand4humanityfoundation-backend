<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Models\Donation;
use App\Models\User;

class DonationPolicy
{
    /**
     * Determine whether the user can view the donor ledger.
     */
    public function viewAny(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Donors);
    }

    /**
     * Determine whether the user can view a donor record.
     */
    public function view(User $user, Donation $donation): bool
    {
        return $user->canOpenPortal(PortalPermission::Donors);
    }

    /**
     * Determine whether the user can record a donation from the desk.
     */
    public function create(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Donors);
    }

    /**
     * Determine whether the user can remove a donor record.
     */
    public function delete(User $user, Donation $donation): bool
    {
        return $user->canOpenPortal(PortalPermission::Donors);
    }
}
