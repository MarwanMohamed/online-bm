<?php

namespace App\Providers\App\Listeners;

use App\Events\UserCreated;
use App\Notifications\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmail
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->notify(new WelcomeNotification($event->password, $event->referrer));
    }
}
