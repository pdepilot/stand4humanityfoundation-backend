<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class SaturdayMeeting
{
    public const string TIMEZONE = 'Africa/Lagos';

    public static function now(): Carbon
    {
        return now()->timezone(self::TIMEZONE);
    }

    /**
     * Attendance can be marked on Saturday from 9:00 up to 10:00.
     */
    public static function isOpen(?Carbon $moment = null): bool
    {
        $moment = self::inLagos($moment);

        if (! $moment->isSaturday()) {
            return false;
        }

        $opens = $moment->copy()->setTime(9, 0);
        $closes = $moment->copy()->setTime(10, 0);

        return $moment->greaterThanOrEqualTo($opens) && $moment->lessThan($closes);
    }

    /**
     * The Saturday whose roll is being marked while the desk is open.
     */
    public static function openMeetingDate(?Carbon $moment = null): ?Carbon
    {
        if (! self::isOpen($moment)) {
            return null;
        }

        return self::inLagos($moment)->startOfDay();
    }

    /**
     * Closed Saturday meetings, most recent first.
     *
     * @return list<string>
     */
    public static function recentClosedMeetings(int $limit, ?Carbon $moment = null): array
    {
        $moment = self::inLagos($moment);
        $saturday = $moment->copy()->startOfDay();

        if (! $saturday->isSaturday()) {
            $saturday = $saturday->previous(Carbon::SATURDAY);
        } elseif ($moment->lessThan($saturday->copy()->setTime(10, 0))) {
            $saturday = $saturday->subWeek();
        }

        $dates = [];

        while (count($dates) < $limit) {
            $dates[] = $saturday->toDateString();
            $saturday = $saturday->subWeek();
        }

        return $dates;
    }

    private static function inLagos(?Carbon $moment): Carbon
    {
        return ($moment ?? now())->copy()->timezone(self::TIMEZONE);
    }
}
