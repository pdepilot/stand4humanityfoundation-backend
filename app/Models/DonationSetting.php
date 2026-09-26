<?php

namespace App\Models;

use App\Enums\DonationCause;
use Database\Factories\DonationSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'child_protection_goal',
    'rescue_goal',
    'victim_support_goal',
    'spent_amount',
])]
class DonationSetting extends Model
{
    /** @use HasFactory<DonationSettingFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'child_protection_goal' => 0,
        'rescue_goal' => 0,
        'victim_support_goal' => 0,
        'spent_amount' => 0,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'child_protection_goal' => 'decimal:2',
            'rescue_goal' => 'decimal:2',
            'victim_support_goal' => 'decimal:2',
            'spent_amount' => 'decimal:2',
        ];
    }

    /**
     * The single donation-desk setting row.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'child_protection_goal' => 0,
            'rescue_goal' => 0,
            'victim_support_goal' => 0,
            'spent_amount' => 0,
        ]);
    }

    public function goalFor(DonationCause $cause): float
    {
        return (float) $this->{$cause->goalColumn()};
    }
}
