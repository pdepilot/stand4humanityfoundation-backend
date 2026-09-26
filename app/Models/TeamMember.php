<?php

namespace App\Models;

use Database\Factories\TeamMemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'role',
    'photo',
    'facebook_url',
    'x_url',
    'instagram_url',
    'youtube_url',
    'sort_order',
])]
class TeamMember extends Model
{
    /** @use HasFactory<TeamMemberFactory> */
    use HasFactory;

    /**
     * Public address for the portrait, whether it was uploaded or seeded from the site images.
     */
    public function photoUrl(): string
    {
        if (! str_starts_with($this->photo, 'team/')) {
            return asset($this->photo);
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->photo);
    }

    /**
     * Social profiles that have a real address.
     *
     * @return list<array{label: string, icon: string, url: string}>
     */
    public function socialLinks(): array
    {
        return array_values(array_filter([
            $this->socialLink('Facebook', 'fab fa-facebook-f', $this->facebook_url),
            $this->socialLink('X', 'fab fa-x-twitter', $this->x_url),
            $this->socialLink('Instagram', 'fab fa-instagram', $this->instagram_url),
            $this->socialLink('YouTube', 'fab fa-youtube', $this->youtube_url),
        ]));
    }

    /**
     * @return array{label: string, icon: string, url: string}|null
     */
    private function socialLink(string $label, string $icon, ?string $url): ?array
    {
        if (! is_string($url) || $url === '') {
            return null;
        }

        return [
            'label' => $label,
            'icon' => $icon,
            'url' => $url,
        ];
    }
}
