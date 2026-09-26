<?php

namespace App\Models;

use Database\Factories\SiteProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'address',
    'phone',
    'email',
    'x_url',
    'facebook_url',
    'instagram_url',
    'youtube_url',
    'linkedin_url',
    'weekday_label',
    'weekday_hours',
    'saturday_label',
    'saturday_hours',
    'extra_label',
    'extra_hours',
    'gallery_1',
    'gallery_2',
    'gallery_3',
    'gallery_4',
    'gallery_5',
    'gallery_6',
])]
class SiteProfile extends Model
{
    /** @use HasFactory<SiteProfileFactory> */
    use HasFactory;

    /**
     * The public office, social links, hours, and footer gallery.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'address' => 'Owerri, Imo State, Nigeria',
            'phone' => '+234 800 723 3248',
            'email' => 'info@standforhumanityfoundation.org',
            'weekday_label' => 'Monday - Friday',
            'weekday_hours' => '09:00 am - 07:00 pm',
            'saturday_label' => 'Saturday',
            'saturday_hours' => '09:00 am - 12:00 pm',
            'extra_label' => 'Emergency Hotline',
            'extra_hours' => '24/7',
        ]);
    }

    /**
     * Social profiles that have a real address, in footer order.
     *
     * @return list<array{label: string, icon: string, url: string}>
     */
    public function socialLinks(): array
    {
        return array_values(array_filter([
            $this->socialLink('X', 'fab fa-x-twitter', $this->x_url),
            $this->socialLink('Facebook', 'fab fa-facebook-f', $this->facebook_url),
            $this->socialLink('Instagram', 'fab fa-instagram', $this->instagram_url),
            $this->socialLink('YouTube', 'fab fa-youtube', $this->youtube_url),
            $this->socialLink('LinkedIn', 'fab fa-linkedin-in', $this->linkedin_url),
        ]));
    }

    /**
     * The three icons shown in the top navigation.
     *
     * @return list<array{label: string, icon: string, url: string}>
     */
    public function headerSocialLinks(): array
    {
        return array_values(array_filter(
            $this->socialLinks(),
            fn (array $link): bool => in_array($link['label'], ['X', 'Facebook', 'YouTube'], true),
        ));
    }

    /**
     * Footer gallery slots, using the original pictures until an administrator replaces one.
     *
     * @return list<array{url: string, alt: string}>
     */
    public function gallery(): array
    {
        $alts = [
            1 => 'Community outreach gallery',
            2 => 'Volunteer engagement gallery',
            3 => 'Advocacy event gallery',
            4 => 'Rescue awareness gallery',
            5 => 'Community meeting gallery',
            6 => 'Public safety campaign gallery',
        ];

        $images = [];

        foreach ($alts as $slot => $alt) {
            $images[] = [
                'url' => $this->imageUrl($this->{'gallery_'.$slot}, 'Charitize/img/gallery-'.$slot.'.jpg'),
                'alt' => $alt,
            ];
        }

        return $images;
    }

    public function imageUrl(?string $path, string $fallback): string
    {
        if (! is_string($path) || $path === '') {
            return asset($fallback);
        }

        if (! str_starts_with($path, 'site/')) {
            return asset($path);
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($path);
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
