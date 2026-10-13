<?php

namespace App\Models;

use App\Enums\PortalPermission;
use Database\Factories\AdminRoleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'permissions'])]
class AdminRole extends Model
{
    /** @use HasFactory<AdminRoleFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    /**
     * @return HasMany<User, $this>
     */
    public function administrators(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function allows(PortalPermission $permission): bool
    {
        $permissions = $this->permissions;

        return is_array($permissions) && in_array($permission->value, $permissions, true);
    }
}
