<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use Illuminate\Database\Seeder;

class NewsArticleSeeder extends Seeder
{
    /**
     * Seed the foundation journal with the current programme notes.
     */
    public function run(): void
    {
        $articles = [
            [
                'slug' => 'child-protection',
                'title' => 'Child protection and rescue: standing with children in danger',
                'excerpt' => 'How we support and rescue children facing abuse, exploitation, neglect, or unsafe living conditions — with confidentiality first.',
                'body' => <<<'MD'
Stand For Humanity Foundation supports and rescues children facing abuse, exploitation, neglect, or unsafe living conditions. We work with families and communities in Owerri, Imo State, to help children move toward safety.

Protection comes before publicity. We do not publish names or photographs of minors.

## What this work covers

Child protection here means listening when a child or caregiver asks for help, and acting with care. It can include:

- Abuse or violence against a child
- Exploitation
- Neglect
- Unsafe living conditions

## How we work

We treat every report as confidential. We do not turn a child's crisis into content. Where a child is in immediate physical danger, families should contact the Nigeria Police in parallel. We are a humanitarian foundation, not a substitute for emergency services.

> We stand with those who cannot stand alone — especially children who need an adult to speak and act for them.

## How to get help

If a child is in danger, use [Report Live](/report) or [Contact](/contact). The emergency telephone line is **+234 800 723 3248**.

*This page explains our programme. It is not a report of a specific case. Photographs show public programme activity, not victims.*
MD,
                'image' => 'event-3.jpg',
                'image_alt' => 'Child protection volunteers meeting families in Owerri',
                'author' => 'Foundation desk',
                'reading_minutes' => 4,
                'tags' => ['Child Protection', 'Rescue', 'Owerri'],
                'search_keywords' => 'child protection rescue children abuse exploitation neglect unsafe families owerri',
                'is_popular' => true,
                'published_at' => '2026-09-15 10:00:00',
            ],
            [
                'slug' => 'domestic-abuse',
                'title' => 'Domestic abuse intervention: helping people seek safety',
                'excerpt' => 'We support victims of domestic violence and help vulnerable individuals seek safety and appropriate assistance.',
                'body' => <<<'MD'
Stand For Humanity Foundation supports victims of domestic violence and helps vulnerable individuals seek safety and appropriate assistance. We treat every report with confidentiality.

This work is not a public spectacle. We do not publish names, photographs, or identifying details of survivors. Protection comes first.

## What seeking safety can look like

People reach us when a home is no longer safe, when leaving feels impossible, or when they need someone to listen without spreading the story. We help them seek appropriate assistance — including through Report Live and our contact desk.

## What we will not do

- Publish a survivor's name or image
- Treat a report as content
- Ask anyone to prove pain in public before we will listen

> We stand with those who cannot stand alone — especially when leaving an unsafe home feels impossible.

## How to get help

If you or someone you know needs help, use [Report Live](/report) or [Contact](/contact). If there is immediate physical danger, contact the Nigeria Police in parallel. The hotline is **+234 800 723 3248**.

*This page explains our programme. It is not a report of a specific case.*
MD,
                'image' => 'pic1.jpg',
                'image_alt' => 'Community volunteers coordinating support for people seeking safety',
                'author' => 'Foundation desk',
                'reading_minutes' => 5,
                'tags' => ['Domestic Abuse', 'Victim Support', 'Safety'],
                'search_keywords' => 'domestic abuse violence intervention safety survivors report',
                'is_popular' => true,
                'published_at' => '2026-09-08 10:00:00',
            ],
            [
                'slug' => 'victim-advocacy',
                'title' => 'Victim support and advocacy for people who cannot speak for themselves',
                'excerpt' => 'We give a voice to people who have been victimized, harassed, threatened, or otherwise unable to speak for themselves.',
                'body' => <<<'MD'
Stand For Humanity Foundation gives a voice to people who have been victimized, harassed, threatened, or otherwise unable to speak for themselves.

Advocacy here means listening, documenting what a person wants shared, and helping them seek appropriate assistance — without turning their pain into content.

## What advocacy is

- Staying with someone until they are heard
- Writing down only what they consent to share
- Helping them reach the right desk, not a public feed

## What advocacy is not

It is not a slogan. It is not a photograph of a person at their lowest point. Individual case details are confidential.

> A voice is not a slogan. It is someone who will stay with you until you are heard.

If you need support, write us through [Contact](/contact) or file a report through [Report Live](/report).

*Individual case details are confidential.*
MD,
                'image' => 'pic3.jpg',
                'image_alt' => 'Advocacy meeting on victim support and child protection',
                'author' => 'Foundation desk',
                'reading_minutes' => 4,
                'tags' => ['Victim Support', 'Advocacy'],
                'search_keywords' => 'victim advocacy support voice harassment threats',
                'is_popular' => true,
                'published_at' => '2026-09-01 10:00:00',
            ],
            [
                'slug' => 'volunteer-bootcamp',
                'title' => 'How we work with volunteers in Owerri',
                'excerpt' => 'Conduct first: keep a confidence, do not publish unverified names or photographs, and never treat a child or survivor as content.',
                'body' => <<<'MD'
Volunteers help Stand For Humanity Foundation listen, report, and support people in need. Training is about conduct: keep a confidence, do not publish unverified names or photographs, and never treat a child or survivor as content.

## What we ask of volunteers

- Keep a schedule you can keep
- Keep a confidence
- Do not spread unverified alerts
- Support a family without turning their crisis into content

## How to join

If you can keep a confidence and keep a schedule, apply through [Volunteer](/volunteer). Upcoming gatherings are listed on [Events](/events).

*We welcome people in Owerri, Imo State, who want to stand with those who cannot stand alone.*
MD,
                'image' => 'event-2.jpg',
                'image_alt' => 'Foundation volunteer training session',
                'author' => 'Foundation desk',
                'reading_minutes' => 4,
                'tags' => ['Volunteers', 'Owerri'],
                'search_keywords' => 'volunteers orientation training owerri conduct confidentiality',
                'is_popular' => false,
                'published_at' => '2026-08-09 10:00:00',
            ],
            [
                'slug' => 'report-live-hours',
                'title' => 'How to report a case',
                'excerpt' => 'Use Report Live for missing persons, abuse, kidnapping, and other emergencies. Use the phone when a person is in danger now.',
                'body' => <<<'MD'
Urgent cases do not respect office hours. Use [Report Live](/report) for missing persons, abuse, kidnapping, and other emergencies. Use the phone when a person is in danger now.

## When to use Report Live

- A person is missing
- A child is in danger
- Someone is being abused or threatened
- You need the desk to take a confidential report

## When to call the police as well

If you are in immediate physical danger, contact the Nigeria Police in parallel. We are a humanitarian foundation, not a substitute for emergency services.

*The emergency telephone line is **+234 800 723 3248**.*
MD,
                'image' => 'pic4.jpg',
                'image_alt' => 'Emergency response coordination desk',
                'author' => 'Foundation desk',
                'reading_minutes' => 3,
                'tags' => ['Report Live', 'Emergency'],
                'search_keywords' => 'report live case emergency hotline missing abuse kidnapping',
                'is_popular' => true,
                'published_at' => '2026-07-30 10:00:00',
            ],
            [
                'slug' => 'awareness-walk',
                'title' => 'Community awareness and the duty to protect vulnerable people',
                'excerpt' => "Practical awareness: how to report a case, how to keep a child's photograph off public channels, and how to stand with a neighbour.",
                'body' => <<<'MD'
Stand For Humanity Foundation promotes awareness about abuse, violence, human rights, child protection, and the responsibility of communities to protect vulnerable people.

Awareness work is practical: how to report a case, how to keep a child's photograph off public channels, and how to stand with a neighbour without humiliation.

## What neighbours can do

- Report a concern through the proper desk instead of a public group
- Refuse to share a child's photograph as an alert
- Stand with a neighbour without exposing their name

If you missed a gathering, details of the next Community Awareness Open Day are on [Events](/events).

*Photographs in this journal show public programme activity, not victims of crime.*
MD,
                'image' => 'event-1.jpg',
                'image_alt' => 'Human rights awareness walk in Owerri',
                'author' => 'Foundation desk',
                'reading_minutes' => 4,
                'tags' => ['Community Awareness', 'Human Rights'],
                'search_keywords' => 'community awareness walk human rights child protection duty',
                'is_popular' => false,
                'published_at' => '2026-08-02 10:00:00',
            ],
            [
                'slug' => 'founders-note',
                'title' => 'Our stand: we stand with those who cannot stand alone',
                'excerpt' => 'Stand For Humanity Foundation exists so children, survivors of abuse, and people in need are not left without a voice or a helping hand.',
                'body' => <<<'MD'
Stand For Humanity Foundation is one house with one name. We protect children, support victims of abuse, assist people affected by kidnapping and other victimization, and help people in need.

We will not invent numbers to look larger than we are. What we can refuse is silence. If a briefing helps you act, act. If you have a case, [report it](/report). If you have time, [volunteer](/volunteer). If you have means, [donate](/donate).

> We stand with those who cannot stand alone.

Dignity is not a slogan we hang on a wall in Owerri. It is a child who is safer, a survivor who is heard, and a neighbour who knows when to speak and when to protect a name.

*Stand For Humanity Foundation, Owerri, Imo State, Nigeria.*
MD,
                'image' => 'pic6.jpg',
                'image_alt' => 'Foundation leadership speaking with community members',
                'author' => 'Foundation leadership',
                'reading_minutes' => 5,
                'tags' => ['Our Stand', 'Advocacy', 'Owerri'],
                'search_keywords' => 'our stand founders note voice voiceless leadership owerri',
                'is_popular' => true,
                'published_at' => '2026-07-12 10:00:00',
            ],
        ];

        foreach ($articles as $article) {
            NewsArticle::query()->updateOrCreate(
                ['slug' => $article['slug']],
                [
                    ...$article,
                    'is_published' => true,
                ],
            );
        }
    }
}
