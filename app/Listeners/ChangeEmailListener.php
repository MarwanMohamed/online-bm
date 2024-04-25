<?php

namespace App\Listeners;

use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\App;

class ChangeEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        /** @var UserService $userService */
        $userService = App::make(UserService::class);

        $userService->sendChangeEmailRequest($event->user, $event->newEmail, $event->referrer);
    }
}
