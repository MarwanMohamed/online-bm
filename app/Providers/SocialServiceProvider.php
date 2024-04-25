<?php

namespace App\Providers;

use App\Contracts\ISocialProvider;
use App\Enums\SocialProvider as SocialProviderEnum;
use App\Services\SocialProviders\GoogleProvider;
use Illuminate\Support\ServiceProvider;

use Illuminate\Http\Request;

class SocialServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ISocialProvider::class, function ($app) {
            $request = app(Request::class);
            $userType = $request->query('provider') ?? SocialProviderEnum::GOOGLE();

            switch ($userType) {
                case SocialProviderEnum::GOOGLE():
                    return app(GoogleProvider::class);

                default:
                    throw new \Exception(trans('errors.invalidCase.userType'));
            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
