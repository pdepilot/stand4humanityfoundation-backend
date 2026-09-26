<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Seed the leadership cards already shown on the public site.
     */
    public function run(): void
    {
        if (TeamMember::query()->exists()) {
            return;
        }

        $members = [
            [
                'name' => 'Foundation Leadership',
                'role' => 'Owerri, Imo State',
                'photo' => 'Charitize/img/stand8.png',
                'sort_order' => 1,
            ],
            [
                'name' => 'Volunteer Coordination',
                'role' => 'Community Programs',
                'photo' => 'Charitize/img/stand5.jpg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Victim Support Coordination',
                'role' => 'Advocacy & Care',
                'photo' => 'Charitize/img/stand4.jpg',
                'sort_order' => 3,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::query()->create($member);
        }
    }
}
