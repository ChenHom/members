<?php

namespace App\Models;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RolePermission extends Pivot
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (self $pivot) {
            // 更新 role permissions 的 cache
            Redis::sAdd(
                Role::rolePermissionsCacheKey($pivot->pivotParent->name->value),
                Permission::find($pivot->permission_id)->name
            );
        });

        static::deleted(function (self $pivot) {
            if ($pivot->pivotParent instanceof Permission) {
                Redis::sRem(
                    Role::rolePermissionsCacheKey(Role::find($pivot->role_id)->value('name')->value),
                    $pivot->pivotParent->name
                );
            } else if ($pivot->pivotParent instanceof Role) {
                Redis::sRem(
                    Role::rolePermissionsCacheKey($pivot->pivotParent->name->value),
                    Permission::find($pivot->permission_id)->name
                );
            }
        });
    }
}
