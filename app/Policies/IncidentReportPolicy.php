<?php

namespace App\Policies;

use App\Models\IncidentReport;
use App\Models\User;

class IncidentReportPolicy
{
    /**
     * Determine whether the user can view the case ledger.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can view a case.
     */
    public function view(User $user, IncidentReport $incidentReport): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can record a case from the desk.
     */
    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can update a case status.
     */
    public function update(User $user, IncidentReport $incidentReport): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can remove a case.
     */
    public function delete(User $user, IncidentReport $incidentReport): bool
    {
        return $user->isAdministrator();
    }
}
