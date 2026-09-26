<?php

namespace Database\Factories;

use App\Models\FoundationMember;
use App\Models\MeetingAttendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MeetingAttendance>
 */
class MeetingAttendanceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'foundation_member_id' => FoundationMember::factory(),
            'meeting_on' => now()->previous('Saturday')->toDateString(),
            'marked_by' => User::factory()->administrator(),
            'marked_at' => now(),
        ];
    }
}
