<?php

namespace App\Models;

use App\Enums\NewsletterSource;
use App\Enums\NewsletterStatus;
use Database\Factories\NewsletterSubscriberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'reference',
    'status',
    'source',
    'email',
    'country',
    'country_code',
    'city',
    'ip_address',
    'unsubscribed_at',
])]
class NewsletterSubscriber extends Model
{
    /** @use HasFactory<NewsletterSubscriberFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => NewsletterStatus::Active->value,
        'source' => NewsletterSource::Public->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => NewsletterStatus::class,
            'source' => NewsletterSource::class,
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function locationLabel(): string
    {
        $parts = array_values(array_filter([
            $this->city,
            $this->country,
        ], fn (?string $value): bool => is_string($value) && $value !== ''));

        return $parts === [] ? '—' : implode(', ', $parts);
    }

    /**
     * @return array{reference: string, status: string}
     */
    public function toPublicArray(): array
    {
        return [
            'reference' => $this->reference,
            'status' => $this->status->value,
        ];
    }
}
