<?php

namespace App\Console\Commands;

use App\Actions\SuspendAbsentMembersAction;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('meetings:suspend-absentees')]
#[Description('Suspend members who missed three Saturday meetings in a row')]
class SuspendAbsentMembers extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(SuspendAbsentMembersAction $suspendAbsentMembers): int
    {
        $count = $suspendAbsentMembers->handle();

        $this->components->info($count === 1 ? 'Suspended 1 member.' : "Suspended {$count} members.");

        return self::SUCCESS;
    }
}
