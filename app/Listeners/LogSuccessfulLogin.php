<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $this->transferToUser($event)->touch('last_signed_in_at');
    }

    protected function transferToUser($event): User
    {
        return $event->user;
    }
}
