<?php

namespace App\Models;

use App\Enums\RoleName;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions(): Collection
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->map(fn ($role) => $role->permissions->pluck('name'))
            ->flatten()
            ->unique()
            ->values();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleName::ADMIN());
    }

    public function hasRole(RoleName $roleName): bool
    {
        return $this->roles()->where('name', $roleName->value)->exists();
    }

    public function hasPermission(string $name): bool
    {
        return in_array($name, $this->permissions(), true);
    }
}
