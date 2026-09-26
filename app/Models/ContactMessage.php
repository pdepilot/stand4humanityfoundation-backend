<?php

namespace App\Models;

use App\Enums\ContactSource;
use App\Enums\ContactStatus;
use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'reference',
    'status',
    'source',
    'name',
    'email',
    'phone',
    'subject',
    'message',
])]
class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => ContactStatus::New->value,
        'source' => ContactSource::Public->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ContactStatus::class,
            'source' => ContactSource::class,
        ];
    }

    /**
     * @return array{reference: string}
     */
    public function toPublicArray(): array
    {
        return [
            'reference' => $this->reference,
        ];
    }
}
