<?php

namespace App\Models;

use App\Enums\PermissionStatus;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected const ALL_PERMISSION_CACHE_KEY = 'all_permissions';

    protected $fillable = ['name', 'status'];

    protected $casts = [
        'status' => PermissionStatus::class,
    ];

    protected static function booted()
    {
        static::updated(function (self $permission) {
            // update role cache when permission disabled
            if ($permission->isDisabled()) {
                $permission->roles->each(
                    fn (Role $item) => $item->permissions()->detach($permission)
                );

                Redis::sRem(self::ALL_PERMISSION_CACHE_KEY, $permission->name);
            } else {
                Redis::sAdd(self::ALL_PERMISSION_CACHE_KEY, $permission->name);
            }
        });

        static::created(function (self $permission) {
            if ($permission->isEnabled()) {
                Redis::sAdd(self::ALL_PERMISSION_CACHE_KEY, $permission->name);
            }
        });
    }

    public function isEnabled(): bool
    {
        return $this->status === PermissionStatus::ENABLED;
    }

    public function isDisabled(): bool
    {
        return $this->status === PermissionStatus::DISABLED;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->using(RolePermission::class);
    }

    public static function cachedAllPermission(): array
    {
        return Redis::sMembers(self::ALL_PERMISSION_CACHE_KEY) ?? [];
    }
}
