<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Models\SiteProfile;
use App\Models\User;

class SiteProfilePolicy
{
    /**
     * Determine whether the user can view the public site desk.
     */
    public function view(User $user, SiteProfile $siteProfile): bool
    {
        return $user->canOpenPortal(PortalPermission::Site);
    }

    /**
     * Determine whether the user can change the public site details.
     */
    public function update(User $user, SiteProfile $siteProfile): bool
    {
        return $user->canOpenPortal(PortalPermission::Site);
    }
}
