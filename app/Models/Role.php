<?php

namespace App\Models;

use App\Enums\RoleName;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => RoleName::class,
    ];

    protected const ROLE_PERMISSION_CACHE_KEY = 'role_permissions';

    public static function rolePermissionsCacheKey(string $roleName): string
    {
        return sprintf('%s_%s', self::ROLE_PERMISSION_CACHE_KEY, $roleName);
    }

    public static function cachedPermissions(string $roleName): array
    {
        return Redis::sMembers(self::rolePermissionsCacheKey($roleName));
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->using(RolePermission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
