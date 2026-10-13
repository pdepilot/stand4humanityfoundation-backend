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
    'impact_video_url',
    'impact_video_caption',
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

    /**
     * The sentence beside the play button on every public page that shows it.
     */
    public function impactVideoCaption(): string
    {
        $caption = trim((string) $this->impact_video_caption);

        if ($caption === '') {
            return 'We stand with those who cannot stand alone — protecting children, supporting survivors, and helping people in need.';
        }

        return $caption;
    }

    /**
     * An embed address that plays inside the site, or null when no YouTube video is set.
     */
    public function impactVideoEmbedUrl(): ?string
    {
        $videoId = self::youtubeVideoId($this->impact_video_url);

        if ($videoId === null) {
            return null;
        }

        return 'https://www.youtube.com/embed/'.$videoId;
    }

    public static function youtubeVideoId(?string $url): ?string
    {
        if (! is_string($url) || trim($url) === '') {
            return null;
        }

        $parts = parse_url(trim($url));

        if (! is_array($parts) || ! isset($parts['host'])) {
            return null;
        }

        $host = strtolower((string) $parts['host']);

        if (str_starts_with($host, 'www.')) {
            $host = substr($host, 4);
        }

        if (! in_array($host, ['youtube.com', 'm.youtube.com', 'youtu.be', 'youtube-nocookie.com'], true)) {
            return null;
        }

        if ($host === 'youtu.be') {
            $videoId = explode('/', trim((string) ($parts['path'] ?? ''), '/'))[0] ?? '';

            return self::validYoutubeId($videoId);
        }

        $path = (string) ($parts['path'] ?? '');

        if (preg_match('#/(?:embed|shorts|live)/([A-Za-z0-9_-]{11})#', $path, $matches) === 1) {
            return $matches[1];
        }

        parse_str((string) ($parts['query'] ?? ''), $query);
        $videoId = $query['v'] ?? null;

        return is_string($videoId) ? self::validYoutubeId($videoId) : null;
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

    private static function validYoutubeId(string $videoId): ?string
    {
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId) !== 1) {
            return null;
        }

        return $videoId;
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
