<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'suspended_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => UserRole::Staff->value,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'suspended_at' => 'datetime',
        ];
    }

    /**
     * Determine whether the user is the super administrator.
     */
    public function isSuperAdministrator(): bool
    {
        return $this->role === UserRole::SuperAdministrator;
    }

    /**
     * Determine whether the user may use the administrator desks.
     */
    public function isAdministrator(): bool
    {
        return $this->role === UserRole::Administrator || $this->isSuperAdministrator();
    }

    /**
     * Determine whether the account is blocked from the portal.
     */
    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    /**
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    #[Scope]
    protected function administrators(Builder $query): Builder
    {
        return $query->where('role', UserRole::Administrator);
    }
}
