<?php

namespace App\Models;

use Database\Factories\MeetingAttendanceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable([
    'foundation_member_id',
    'meeting_on',
    'marked_by',
    'marked_at',
])]
class MeetingAttendance extends Model
{
    /** @use HasFactory<MeetingAttendanceFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'meeting_on' => 'date',
            'marked_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<FoundationMember, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(FoundationMember::class, 'foundation_member_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function marker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function meetingLabel(): string
    {
        $meetingOn = $this->meeting_on;

        return $meetingOn instanceof Carbon ? $meetingOn->format('j M Y') : '';
    }
}
