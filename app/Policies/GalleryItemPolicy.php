<?php

namespace App\Policies;

use App\Enums\PortalPermission;
use App\Models\GalleryItem;
use App\Models\User;

class GalleryItemPolicy
{
    /**
     * Determine whether the user can view the gallery desk.
     */
    public function viewAny(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Gallery);
    }

    /**
     * Determine whether the user can view a gallery item.
     */
    public function view(User $user, GalleryItem $galleryItem): bool
    {
        return $user->canOpenPortal(PortalPermission::Gallery);
    }

    /**
     * Determine whether the user can add a gallery item.
     */
    public function create(User $user): bool
    {
        return $user->canOpenPortal(PortalPermission::Gallery);
    }

    /**
     * Determine whether the user can change a gallery item.
     */
    public function update(User $user, GalleryItem $galleryItem): bool
    {
        return $user->canOpenPortal(PortalPermission::Gallery);
    }

    /**
     * Determine whether the user can remove a gallery item.
     */
    public function delete(User $user, GalleryItem $galleryItem): bool
    {
        return $user->canOpenPortal(PortalPermission::Gallery);
    }
}
