<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

#[Fillable([
    'ip_address',
    'failures',
    'stage',
    'banned_until',
])]
class PortalLoginBan extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'failures' => 'integer',
            'stage' => 'integer',
            'banned_until' => 'datetime',
        ];
    }

    public function isPermanent(): bool
    {
        return $this->stage >= 3;
    }

    public function blockLabel(): string
    {
        if ($this->isPermanent()) {
            return 'Permanent';
        }

        if ($this->stage >= 2) {
            return '72 hours';
        }

        return '24 hours';
    }

    public function isActive(): bool
    {
        if ($this->isPermanent()) {
            return true;
        }

        $until = $this->banned_until;

        return $until instanceof Carbon && $until->isFuture();
    }
}
