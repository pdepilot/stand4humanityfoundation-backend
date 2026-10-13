<?php

namespace App\Enums;

enum PortalPermission: string
{
    public const DENIED_MESSAGE = 'You are not permitted to view or visit this page.';

    case Donors = 'donors';
    case Cases = 'cases';
    case News = 'news';
    case Subscribers = 'subscribers';
    case Events = 'events';
    case Volunteers = 'volunteers';
    case Contacts = 'contacts';
    case Members = 'members';
    case Team = 'team';
    case Testimonies = 'testimonies';
    case Attendance = 'attendance';
    case Gallery = 'gallery';
    case Mail = 'mail';
    case Audit = 'audit';
    case Bans = 'bans';
    case Site = 'site';
    case Settings = 'settings';

    public function label(): string
    {
        return match ($this) {
            self::Donors => 'Donors',
            self::Cases => 'Cases',
            self::News => 'News',
            self::Subscribers => 'Subscribers',
            self::Events => 'Events',
            self::Volunteers => 'Volunteers',
            self::Contacts => 'Contact',
            self::Members => 'Members',
            self::Team => 'Team',
            self::Testimonies => 'Testimonies',
            self::Attendance => 'Attendance',
            self::Gallery => 'Gallery',
            self::Mail => 'Mail',
            self::Audit => 'Audit',
            self::Bans => 'Bans',
            self::Site => 'Site details',
            self::Settings => 'Settings',
        };
    }

    public static function fromRouteName(?string $name): ?self
    {
        return match (true) {
            $name === null => null,
            str_starts_with($name, 'portal.donors'), str_starts_with($name, 'portal.donation-goal') => self::Donors,
            str_starts_with($name, 'portal.cases') => self::Cases,
            str_starts_with($name, 'portal.news') => self::News,
            str_starts_with($name, 'portal.subscribers') => self::Subscribers,
            str_starts_with($name, 'portal.events') => self::Events,
            str_starts_with($name, 'portal.volunteers') => self::Volunteers,
            str_starts_with($name, 'portal.contacts') => self::Contacts,
            str_starts_with($name, 'portal.members.attendance') => self::Attendance,
            str_starts_with($name, 'portal.members') => self::Members,
            str_starts_with($name, 'portal.team') => self::Team,
            str_starts_with($name, 'portal.testimonies') => self::Testimonies,
            str_starts_with($name, 'portal.gallery') => self::Gallery,
            str_starts_with($name, 'portal.mail') => self::Mail,
            str_starts_with($name, 'portal.audit') => self::Audit,
            str_starts_with($name, 'portal.login-bans') => self::Bans,
            str_starts_with($name, 'portal.site') => self::Site,
            str_starts_with($name, 'portal.settings') => self::Settings,
            default => null,
        };
    }
}
