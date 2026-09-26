<?php

namespace Database\Seeders;

use App\Enums\EventAction;
use App\Models\FoundationEvent;
use Illuminate\Database\Seeder;

class FoundationEventSeeder extends Seeder
{
    /**
     * Seed the gatherings shown on the public events page.
     */
    public function run(): void
    {
        $events = [
            [
                'slug' => 'human-rights-awareness-walk',
                'title' => 'Human Rights Awareness Walk',
                'summary' => 'A peaceful city walk to spotlight child protection, abuse prevention, and community responsibility across Imo State.',
                'description' => 'A peaceful city walk to spotlight child protection, abuse prevention, and community responsibility across Imo State.',
                'image' => 'event-1.jpg',
                'image_alt' => 'Human rights awareness walk',
                'location' => 'Owerri, Imo State',
                'audience' => null,
                'highlights' => null,
                'action' => EventAction::Contact,
                'starts_at' => '2026-08-02 07:00:00',
                'ends_at' => '2026-08-02 11:00:00',
                'is_featured' => false,
            ],
            [
                'slug' => 'volunteer-orientation',
                'title' => 'Volunteer Orientation',
                'summary' => 'Hands-on training for community support, emergency reporting, child protection awareness, and volunteer coordination.',
                'description' => 'Hands-on training for community support, emergency reporting, child protection awareness, and volunteer coordination.',
                'image' => 'event-2.jpg',
                'image_alt' => 'foundation volunteer training',
                'location' => 'Owerri, Imo State',
                'audience' => null,
                'highlights' => null,
                'action' => EventAction::Volunteer,
                'starts_at' => '2026-08-09 10:00:00',
                'ends_at' => '2026-08-09 15:00:00',
                'is_featured' => false,
            ],
            [
                'slug' => 'victim-support-advocacy-session',
                'title' => 'Victim Support & Advocacy Session',
                'summary' => 'A community session for people seeking help after abuse, harassment, or other victimization — focused on dignity, safety, and practical next steps.',
                'description' => 'A community session for people seeking help after abuse, harassment, or other victimization — focused on dignity, safety, and practical next steps.',
                'image' => 'event-3.jpg',
                'image_alt' => 'Victim support and advocacy community session',
                'location' => 'Owerri, Imo State',
                'audience' => null,
                'highlights' => null,
                'action' => EventAction::Services,
                'starts_at' => '2026-08-15 09:00:00',
                'ends_at' => '2026-08-15 14:00:00',
                'is_featured' => false,
            ],
            [
                'slug' => 'community-awareness-open-day',
                'title' => 'Community Awareness Open Day',
                'summary' => 'A public gathering for families, volunteers, and community members focused on child protection and victim support.',
                'description' => 'A public gathering for families, volunteers, and community members — focused on child protection, victim support, and humanitarian help in Owerri, Imo State. Contact us to take part. Date details are confirmed through our newsroom and contact desk.',
                'image' => 'event-3.jpg',
                'image_alt' => 'Community awareness gathering for child protection and victim support',
                'location' => 'Owerri, Imo State',
                'audience' => 'Open to the public',
                'highlights' => [
                    'Child protection and victim support information',
                    'Help for people facing abuse or hardship',
                    'Volunteer onboarding for community support',
                ],
                'action' => EventAction::Contact,
                'starts_at' => '2026-08-18 09:00:00',
                'ends_at' => '2026-08-18 16:00:00',
                'is_featured' => true,
            ],
            [
                'slug' => 'missing-persons-briefing',
                'title' => 'Missing Persons Briefing',
                'summary' => 'Families and volunteers learn how to report cases, share verified alerts, and support searches with care and accuracy.',
                'description' => 'Families and volunteers learn how to report cases, share verified alerts, and support searches with care and accuracy.',
                'image' => 'pic1.jpg',
                'image_alt' => 'Missing persons community briefing',
                'location' => 'Owerri, Imo State',
                'audience' => null,
                'highlights' => null,
                'action' => EventAction::Contact,
                'starts_at' => '2026-08-24 12:00:00',
                'ends_at' => '2026-08-24 16:00:00',
                'is_featured' => false,
            ],
            [
                'slug' => 'youth-safety-town-hall',
                'title' => 'Youth Safety Town Hall',
                'summary' => 'Open dialogue with young people on street safety, digital alerts, peer reporting, and building trust in every neighbourhood.',
                'description' => 'Open dialogue with young people on street safety, digital alerts, peer reporting, and building trust in every neighbourhood.',
                'image' => 'pic2.jpg',
                'image_alt' => 'Youth and neighbourhood safety forum',
                'location' => 'Owerri, Imo State',
                'audience' => null,
                'highlights' => null,
                'action' => EventAction::Mission,
                'starts_at' => '2026-09-06 14:00:00',
                'ends_at' => '2026-09-06 17:00:00',
                'is_featured' => false,
            ],
            [
                'slug' => 'hope-and-humanity-benefit-night',
                'title' => 'Hope & Humanity Benefit Night',
                'summary' => 'An evening of stories, music, and giving — raising funds for child protection, victim support, and humanitarian assistance.',
                'description' => 'An evening of stories, music, and giving — raising funds for child protection, victim support, and humanitarian assistance.',
                'image' => 'pic5.jpg',
                'image_alt' => 'Fundraising evening for child protection and victim support',
                'location' => 'Owerri, Imo State',
                'audience' => null,
                'highlights' => null,
                'action' => EventAction::Donate,
                'starts_at' => '2026-09-20 18:00:00',
                'ends_at' => '2026-09-20 21:30:00',
                'is_featured' => false,
            ],
        ];

        foreach ($events as $event) {
            FoundationEvent::query()->updateOrCreate(
                ['slug' => $event['slug']],
                $event,
            );
        }
    }
}
