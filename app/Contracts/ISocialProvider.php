<?php

namespace App\Contracts;

use Laravel\Socialite\Two\User;

interface ISocialProvider
{
    /**
     * Get Redirect Url to the authentication page for the provider.
     */
    public function getAuthRedirectUrl(): string;

    /**
     *  Get the User instance for the authenticated user.
     * @param $redirectUrl
     * @return User
     */
    public function getUser($redirectUrl): User;


    /**
     * Handle Callback URL
     */
    public function handleLoginCallback(User $user);

}
