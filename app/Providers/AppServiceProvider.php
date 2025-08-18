<?php

namespace App\Providers;

use App\Jobs\PlayNotificationSound;
use App\Models\Insurance;
use App\Models\User;
use App\Observers\InsuranceObserver;
use App\Observers\UserObserver;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;
use Rollbar\Laravel\RollbarServiceProvider;
use Filament\Support\Assets\Js;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Password::defaults(function () {
            return Password::min(8)
                ->rules(['between:8,15']);
        });

        FilamentAsset::register([
            Js::make('custom', public_path('/js/custom.js')),
        ]);


        Insurance::observe(InsuranceObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        if ($this->app->environment('production')) {
//            $this->app->register(RollbarServiceProvider::class);
//        }
    }
}
