<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class RecordLastLogin
{
    public function handle(Login $event): void
    {
        $event->user->updateQuietly(['last_login_at' => now()]);
    }
}
