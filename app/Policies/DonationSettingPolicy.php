<?php

namespace App\Policies;

use App\Models\DonationSetting;
use App\Models\User;

class DonationSettingPolicy
{
    /**
     * Determine whether the user can set the public donation goal.
     */
    public function update(User $user, DonationSetting $donationSetting): bool
    {
        return $user->isAdministrator();
    }
}
