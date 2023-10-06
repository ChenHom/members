<?php

namespace App\Models;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRole extends Pivot
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(fn (self $pivot) => $pivot->freshUserRoleCache());
        static::deleted(fn (self $pivot) => $pivot->freshUserRoleCache());
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    protected function freshUserRoleCache(): void
    {
        Redis::hSet(
            User::USER_ROLE_CACHE_KEY,
            $this->pivotParent->id,
            json_encode([
                'value' => $this->pivotParent->roles()->pluck('name')->all(),
            ])
        );
    }
}
