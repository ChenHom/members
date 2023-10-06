<?php

namespace App\Models;

use App\Enums\RoleName;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public const USER_ROLE_CACHE_KEY = 'user_roles';

    public function cachedRoles(): array
    {
        $roles = json_decode(
            Redis::hGet(static::USER_ROLE_CACHE_KEY, $this->id)
        );

        return data_get($roles, 'value', []);
    }

    public function cachedPermissions(): array
    {
        foreach ($this->cachedRoles() as $roleName) {
            $roles[$roleName] = Role::cachedPermissions($roleName);
        }

        return $roles ?? [];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->using(UserRole::class);
    }

    public function permissions(): Collection
    {
        return collect($this->cachedPermissions())->flatten()->unique();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleName::ADMIN);
    }

    public function hasRole(RoleName $name): bool
    {
        return collect($this->cachedRoles())->contains($name->value);
    }

    public function hasPermission(string $name): bool
    {
        return $this->permissions()->contains($name);
    }
}
