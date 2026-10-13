<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Models\PortalLoginBan;
use App\Models\User;

class PortalLoginBanPolicy
{
    /**
     * Determine whether the user can view blocked devices.
     */
    public function viewAny(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Bans);
    }

    /**
     * Determine whether the user can lift a block.
     */
    public function delete(User $user, PortalLoginBan $portalLoginBan): bool
    {
        return $user->canOpenPortal(PortalPermission::Bans);
    }
}
