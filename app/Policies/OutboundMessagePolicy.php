<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Models\OutboundMessage;
use App\Models\User;

class OutboundMessagePolicy
{
    /**
     * Determine whether the user can open the email center.
     */
    public function viewAny(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Mail);
    }

    /**
     * Determine whether the user can send a foundation message.
     */
    public function create(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Mail);
    }

    /**
     * Determine whether the user can view a sent message.
     */
    public function view(User $user, OutboundMessage $outboundMessage): bool
    {
        return $user->canOpenPortal(PortalPermission::Mail);
    }
}
