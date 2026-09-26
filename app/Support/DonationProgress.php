<?php

namespace App\Support;

use App\Enums\DonationCause;
use App\Models\Donation;
use App\Models\DonationSetting;

class DonationProgress
{
    /**
     * @param  array<string, DonationCauseProgress>  $causes
     */
    public function __construct(
        public float $goal,
        public float $raised,
        public int $percent,
        public array $causes,
        public float $spent = 0,
    ) {}

    /**
     * Live raised totals against the administrator-set programme goals.
     */
    public static function current(): self
    {
        $setting = DonationSetting::current();
        $raisedByCause = Donation::raisedInNgnByCause();
        $causes = [];
        $goal = 0.0;
        $raised = 0.0;

        foreach (DonationCause::publicGoalCauses() as $cause) {
            $causeGoal = $setting->goalFor($cause);
            $causeRaised = $raisedByCause[$cause->value] ?? 0.0;
            $causes[$cause->value] = new DonationCauseProgress(
                $cause,
                $causeGoal,
                $causeRaised,
                self::percent($causeRaised, $causeGoal),
            );
            $goal += $causeGoal;
            $raised += $causeRaised;
        }

        return new self(
            $goal,
            $raised,
            self::percent($raised, $goal),
            $causes,
            (float) $setting->spent_amount,
        );
    }

    public function for(DonationCause $cause): DonationCauseProgress
    {
        return $this->causes[$cause->value];
    }

    public function formattedGoal(): string
    {
        return number_format($this->goal, 0);
    }

    public function formattedRaised(): string
    {
        return number_format($this->raised, 0);
    }

    public function bank(): float
    {
        return max(0, $this->raised - $this->spent);
    }

    public function formattedSpent(): string
    {
        return number_format($this->spent, 0);
    }

    public function formattedBank(): string
    {
        return number_format($this->bank(), 0);
    }

    /**
     * @return array{
     *     goal: float,
     *     raised: float,
     *     percent: int,
     *     spent: float,
     *     bank: float,
     *     goals: array<string, array{cause: string, label: string, goal: float, raised: float, percent: int}>
     * }
     */
    public function toArray(): array
    {
        return [
            'goal' => $this->goal,
            'raised' => $this->raised,
            'percent' => $this->percent,
            'spent' => $this->spent,
            'bank' => $this->bank(),
            'goals' => array_map(
                fn (DonationCauseProgress $progress): array => $progress->toArray(),
                $this->causes,
            ),
        ];
    }

    private static function percent(float $raised, float $goal): int
    {
        if ($goal <= 0) {
            return 0;
        }

        return (int) min(100, round(($raised / $goal) * 100));
    }
}
