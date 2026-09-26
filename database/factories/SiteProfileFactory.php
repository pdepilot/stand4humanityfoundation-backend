<?php

namespace Database\Factories;

use App\Models\SiteProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteProfile>
 */
class SiteProfileFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => 'Owerri, Imo State, Nigeria',
            'phone' => '+234 800 723 3248',
            'email' => 'info@standforhumanityfoundation.org',
            'x_url' => null,
            'facebook_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
            'linkedin_url' => null,
            'weekday_label' => 'Monday - Friday',
            'weekday_hours' => '09:00 am - 07:00 pm',
            'saturday_label' => 'Saturday',
            'saturday_hours' => '09:00 am - 12:00 pm',
            'extra_label' => 'Emergency Hotline',
            'extra_hours' => '24/7',
        ];
    }
}
