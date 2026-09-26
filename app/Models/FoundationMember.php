<?php

namespace App\Models;

use App\Enums\MemberGender;
use App\Enums\MembershipStatus;
use Database\Factories\FoundationMemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'member_number',
    'full_name',
    'photo',
    'gender',
    'date_of_birth',
    'phone',
    'email',
    'address',
    'city',
    'state',
    'occupation',
    'joined_on',
    'status',
    'emergency_contact_name',
    'emergency_contact_phone',
    'notes',
])]
class FoundationMember extends Model
{
    /** @use HasFactory<FoundationMemberFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'state' => 'Imo',
        'status' => MembershipStatus::Active->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender' => MemberGender::class,
            'status' => MembershipStatus::class,
            'date_of_birth' => 'date',
            'joined_on' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'member_number';
    }

    public function photoUrl(): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->photo);
    }

    public function age(): ?int
    {
        if (! $this->date_of_birth instanceof Carbon) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    /**
     * @param  Builder<FoundationMember>  $query
     * @return Builder<FoundationMember>
     */
    #[Scope]
    protected function lookup(Builder $query, string $term): Builder
    {
        $escaped = addcslashes(trim($term), '%_\\');
        $like = '%'.$escaped.'%';

        return $query->where(function (Builder $query) use ($like): void {
            $query->where('full_name', 'like', $like)
                ->orWhere('member_number', 'like', $like);
        });
    }
}
