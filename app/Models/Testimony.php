<?php

namespace App\Models;

use App\Enums\TestimonyStatus;
use Database\Factories\TestimonyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'email',
    'role',
    'body',
    'rating',
    'photo',
    'status',
    'reviewed_at',
    'reviewed_by',
])]
class Testimony extends Model
{
    /** @use HasFactory<TestimonyFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'rating' => 5,
        'status' => TestimonyStatus::Pending->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'status' => TestimonyStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * @param  Builder<Testimony>  $query
     * @return Builder<Testimony>
     */
    #[Scope]
    protected function approved(Builder $query): Builder
    {
        return $query->where('status', TestimonyStatus::Approved);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Public address for the portrait, or a foundation image when none was sent.
     */
    public function photoUrl(): string
    {
        if (! is_string($this->photo) || $this->photo === '') {
            return asset('Charitize/img/stand6.jpg');
        }

        if (! str_starts_with($this->photo, 'testimonies/')) {
            return asset($this->photo);
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->photo);
    }
}
