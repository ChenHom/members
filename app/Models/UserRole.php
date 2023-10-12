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
        static::created(fn (self $pivot) => Redis::sAdd(
            User::USER_ROLE_CACHE_KEY . '_' . $pivot->user_id,
            $pivot->role->name->value
        ));
        static::deleted(fn (self $pivot) => Redis::sRem(
            User::USER_ROLE_CACHE_KEY . '_' . $pivot->user_id,
            $pivot->role->name->value
        ));
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
