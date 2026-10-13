<?php

namespace Database\Factories;

use App\Enums\GalleryMedia;
use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Community outreach',
            'details' => 'Volunteers meeting families in Owerri.',
            'kind' => GalleryMedia::Image,
            'path' => 'gallery/outreach.jpg',
        ];
    }

    public function video(): static
    {
        return $this->state(fn (array $attributes): array => [
            'kind' => GalleryMedia::Video,
            'path' => 'gallery/outreach.mp4',
        ]);
    }
}
