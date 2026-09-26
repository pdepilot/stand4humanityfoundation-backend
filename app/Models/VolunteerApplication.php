<?php

namespace App\Models;

use App\Enums\VolunteerInterest;
use App\Enums\VolunteerSource;
use App\Enums\VolunteerStatus;
use Database\Factories\VolunteerApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'reference',
    'status',
    'source',
    'name',
    'email',
    'phone',
    'city',
    'interest',
    'availability',
    'experience',
    'motivation',
    'photo',
    'admin_notes',
    'reviewed_by',
    'reviewed_at',
])]
class VolunteerApplication extends Model
{
    /** @use HasFactory<VolunteerApplicationFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => VolunteerStatus::Pending->value,
        'source' => VolunteerSource::Public->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => VolunteerStatus::class,
            'source' => VolunteerSource::class,
            'interest' => VolunteerInterest::class,
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function photoUrl(): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->photo);
    }

    /**
     * @return array{reference: string, status: string}
     */
    public function toPublicArray(): array
    {
        return [
            'reference' => $this->reference,
            'status' => $this->status->value,
        ];
    }
}
