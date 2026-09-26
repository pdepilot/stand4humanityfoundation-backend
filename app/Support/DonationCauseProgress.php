<?php

namespace App\Support;

use App\Enums\DonationCause;

class DonationCauseProgress
{
    public function __construct(
        public DonationCause $cause,
        public float $goal,
        public float $raised,
        public int $percent,
    ) {}

    public function formattedGoal(): string
    {
        return number_format($this->goal, 0);
    }

    public function formattedRaised(): string
    {
        return number_format($this->raised, 0);
    }

    /**
     * @return array{cause: string, label: string, goal: float, raised: float, percent: int}
     */
    public function toArray(): array
    {
        return [
            'cause' => $this->cause->value,
            'label' => $this->cause->label(),
            'goal' => $this->goal,
            'raised' => $this->raised,
            'percent' => $this->percent,
        ];
    }
}
