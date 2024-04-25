<?php

namespace App\Providers;

use App\Events\ChangeEmailEvent;
use App\Events\NewRegisteredUserEvent;
use App\Listeners\ChangeEmailListener;
use App\Listeners\SendVerificationEmail;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\UserCreated::class => [
            App\Listeners\SendWelcomeEmail::class,
        ],
        NewRegisteredUserEvent::class=>[
            SendVerificationEmail::class
        ],
        PasswordReset::class => [
            \App\Listeners\EnableEnforceChangePassword::class
        ],

        ChangeEmailEvent::class => [
            ChangeEmailListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }
}
