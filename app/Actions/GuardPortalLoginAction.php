<?php

namespace App\Actions;

use App\Models\PortalLoginBan;

class GuardPortalLoginAction
{
    /**
     * The message shown while this IP cannot sign in, if it is banned.
     */
    public function blockMessage(string $ipAddress): ?string
    {
        $ban = $this->banFor($ipAddress);

        if (! $ban instanceof PortalLoginBan || ! $ban->isActive()) {
            return null;
        }

        if ($ban->isPermanent()) {
            return 'This device is blocked from the portal.';
        }

        if ($ban->stage >= 2) {
            return 'This device is blocked for 72 hours.';
        }

        return 'This device is blocked for 24 hours after three failed sign-in attempts.';
    }

    /**
     * Record a failed sign-in and escalate the ban for this IP.
     */
    public function recordFailure(string $ipAddress): void
    {
        $ban = PortalLoginBan::query()->firstOrCreate(
            ['ip_address' => $ipAddress],
            ['failures' => 0, 'stage' => 0],
        );

        if ($ban->isActive()) {
            return;
        }

        if ($ban->stage <= 0) {
            $ban->failures++;

            if ($ban->failures >= 3) {
                $ban->fill([
                    'failures' => 0,
                    'stage' => 1,
                    'banned_until' => now()->addHours(24),
                ]);
            }

            $ban->save();

            return;
        }

        if ($ban->stage === 1) {
            $ban->update([
                'failures' => 0,
                'stage' => 2,
                'banned_until' => now()->addHours(72),
            ]);

            return;
        }

        $ban->update([
            'failures' => 0,
            'stage' => 3,
            'banned_until' => null,
        ]);
    }

    /**
     * Lift a block so this IP starts again with a clean sign-in record.
     */
    public function lift(PortalLoginBan $ban): void
    {
        $ban->delete();
    }

    /**
     * A successful sign-in clears the current run of failures.
     */
    public function recordSuccess(string $ipAddress): void
    {
        PortalLoginBan::query()
            ->where('ip_address', $ipAddress)
            ->update(['failures' => 0]);
    }

    private function banFor(string $ipAddress): ?PortalLoginBan
    {
        return PortalLoginBan::query()->where('ip_address', $ipAddress)->first();
    }
}
