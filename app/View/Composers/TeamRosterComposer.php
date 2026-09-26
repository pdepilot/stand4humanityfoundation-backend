<?php

namespace App\View\Composers;

use App\Models\TeamMember;
use Illuminate\View\View;

class TeamRosterComposer
{
    /**
     * Share the public team roster with the pages that show it.
     */
    public function compose(View $view): void
    {
        $view->with('teamMembers', TeamMember::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get());
    }
}
