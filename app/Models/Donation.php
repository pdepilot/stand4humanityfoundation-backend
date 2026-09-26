<?php

namespace App\Models;

use App\Enums\DonationCause;
use App\Enums\DonationCurrency;
use App\Enums\DonationFrequency;
use App\Enums\DonationPaymentMethod;
use App\Enums\DonationPaymentStatus;
use App\Enums\DonationSource;
use Database\Factories\DonationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'reference',
    'name',
    'email',
    'phone',
    'location',
    'description',
    'amount',
    'currency',
    'cause',
    'frequency',
    'payment_method',
    'payment_status',
    'provider_reference',
    'paid_at',
    'anonymous',
    'source',
])]
class Donation extends Model
{
    /** @use HasFactory<DonationFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'currency' => DonationCurrency::Ngn->value,
        'frequency' => DonationFrequency::OneTime->value,
        'cause' => DonationCause::General->value,
        'payment_method' => DonationPaymentMethod::Paystack->value,
        'payment_status' => DonationPaymentStatus::Paid->value,
        'anonymous' => false,
        'source' => DonationSource::Public->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'anonymous' => 'boolean',
            'currency' => DonationCurrency::class,
            'cause' => DonationCause::class,
            'frequency' => DonationFrequency::class,
            'payment_method' => DonationPaymentMethod::class,
            'payment_status' => DonationPaymentStatus::class,
            'paid_at' => 'datetime',
            'source' => DonationSource::class,
        ];
    }

    /**
     * Sum of recorded gifts, expressed in Nigerian naira.
     */
    /**
     * @return array<string, float>
     */
    public static function raisedInNgnByCause(): array
    {
        $totals = [];

        foreach (DonationCause::cases() as $cause) {
            $totals[$cause->value] = 0.0;
        }

        foreach (DonationCurrency::cases() as $currency) {
            $rows = static::query()
                ->selectRaw('cause, SUM(amount) as total')
                ->where('currency', $currency)
                ->where('payment_status', DonationPaymentStatus::Paid)
                ->groupBy('cause')
                ->toBase()
                ->get();

            foreach ($rows as $row) {
                $totals[$row->cause] += (float) $row->total * $currency->ngnRate();
            }
        }

        return $totals;
    }

    public static function raisedInNgn(): float
    {
        return array_sum(static::raisedInNgnByCause());
    }

    /**
     * This gift expressed in Nigerian naira.
     */
    public function amountInNgn(): float
    {
        return (float) $this->amount * $this->currency->ngnRate();
    }

    /**
     * Public ledger name, hiding identity when the donor asked to stay anonymous.
     */
    public function publicName(): string
    {
        if ($this->anonymous) {
            return 'Anonymous Donor';
        }

        return $this->name !== '' ? $this->name : 'Supporter';
    }

    /**
     * @param  Builder<Donation>  $query
     * @return Builder<Donation>
     */
    #[Scope]
    protected function settled(Builder $query): Builder
    {
        return $query->where('payment_status', DonationPaymentStatus::Paid);
    }

    /**
     * @return array{
     *     id: string,
     *     name: string,
     *     phone: string,
     *     description: string,
     *     amount: float,
     *     currency: string,
     *     cause: string,
     *     anonymous: bool,
     *     createdAt: string|null
     * }
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->reference,
            'name' => $this->publicName(),
            'phone' => $this->phone,
            'description' => $this->description ?? '',
            'amount' => (float) $this->amount,
            'currency' => $this->currency->value,
            'cause' => $this->cause->value,
            'anonymous' => $this->anonymous,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
