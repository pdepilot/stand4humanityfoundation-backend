<?php

namespace Tests\Unit\Models;

use App\Models\SiteProfile;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SiteProfileTest extends TestCase
{
    #[DataProvider('youtubeAddresses')]
    public function test_a_youtube_address_becomes_an_embed_address(string $address): void
    {
        $profile = new SiteProfile([
            'impact_video_url' => $address,
        ]);

        $this->assertSame(
            'https://www.youtube.com/embed/abcDEF12345',
            $profile->impactVideoEmbedUrl(),
        );
    }

    public function test_a_non_youtube_address_does_not_become_an_embed(): void
    {
        $profile = new SiteProfile([
            'impact_video_url' => 'https://example.com/watch?v=abcDEF12345',
        ]);

        $this->assertNull($profile->impactVideoEmbedUrl());
    }

    public function test_a_blank_video_keeps_the_banner_sentence(): void
    {
        $profile = new SiteProfile;

        $this->assertSame(
            'We stand with those who cannot stand alone — protecting children, supporting survivors, and helping people in need.',
            $profile->impactVideoCaption(),
        );
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function youtubeAddresses(): array
    {
        return [
            'watch link' => ['https://www.youtube.com/watch?v=abcDEF12345'],
            'short link' => ['https://youtu.be/abcDEF12345'],
            'shorts link' => ['https://www.youtube.com/shorts/abcDEF12345'],
            'embed link' => ['https://www.youtube.com/embed/abcDEF12345'],
            'mobile link' => ['https://m.youtube.com/watch?v=abcDEF12345&t=12'],
        ];
    }
}
