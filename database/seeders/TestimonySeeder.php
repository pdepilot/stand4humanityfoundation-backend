<?php

namespace Database\Seeders;

use App\Enums\TestimonyStatus;
use App\Models\Testimony;
use Illuminate\Database\Seeder;

class TestimonySeeder extends Seeder
{
    /**
     * Seed the quotes already shown on the homepage.
     */
    public function run(): void
    {
        if (Testimony::query()->exists()) {
            return;
        }

        $testimonies = [
            [
                'name' => 'Our Promise',
                'role' => 'Stand For Humanity Foundation',
                'body' => 'We stand with those who cannot stand alone — giving voice to the voiceless and help to people who have nowhere else to turn.',
                'photo' => 'Charitize/img/stand6.jpg',
            ],
            [
                'name' => 'Child Protection',
                'role' => 'Core Programme',
                'body' => 'Children facing abuse, neglect, or unsafe conditions deserve protection, care, and a chance to live with dignity.',
                'photo' => 'Charitize/img/stand7.jpg',
            ],
            [
                'name' => 'Victim Support',
                'role' => 'Advocacy & Care',
                'body' => 'We support victims of domestic abuse, harassment, kidnapping, and other forms of victimization — with compassion, confidentiality, and practical help.',
                'photo' => 'Charitize/img/stand4.jpg',
            ],
        ];

        foreach ($testimonies as $testimony) {
            Testimony::query()->create([
                ...$testimony,
                'rating' => 5,
                'status' => TestimonyStatus::Approved,
                'reviewed_at' => now(),
            ]);
        }
    }
}
