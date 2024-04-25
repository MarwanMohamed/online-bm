<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Feature;

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function ()
{
    //Route::post('login', 'LoginController@login');
    Route::group(['middleware'=>"feature-state:".Feature::FORGET_PASSWORD().",1"], function () {
        Route::post('forgot-password', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('reset-password', 'ResetPasswordController@reset')->name('password.reset');
    });


    /* ------------------------------------------------------------------- */
    // development/testing endpoint

    Route::post('create-testing-token', 'LoginController@createTestingToken')->middleware('route-dev');
    Route::post('register', 'RegisterController@register')
        ->middleware("feature-state:".Feature::REGISTER().",1");

    /* ------------------------------------------------------------------- */

    Route::group(['middleware' => 'auth:sanctum'], function ()
    {
        //Route::post('logout', 'LoginController@logout');
        Route::post('change-password', 'ChangePasswordController')
            ->middleware("feature-state:".Feature::CHANGE_PASSWORD().",1");

        Route::post('/verification/send',"\App\Http\Controllers\Api\Auth\VerificationController@sendVerificationEmailToUser")
            ->name('verification.send_to_user')
            ->middleware("role:admin");
    });

});

Route::group(['prefix' => 'auth', 'namespace' => '\App\Http\Controllers\Api\Auth'], function (){

    Route::post('/email/resend',"VerificationController@resendVerificationEmail")
        ->name('verification.resend');

    Route::get('/email/verify/{hash}',"VerificationController@verifyEmail")
        ->name("verification.verify");


    Route::group(['middleware'=>"feature-state:".Feature::SOCIAL_LOGIN().",1"], function (){

        /**
         * Social Logins
         */
        Route::get('/social/{provider}',"SocialLoginController@getProviderUrl")->name("social.getProviderUrl");
        Route::get('/social/{provider}/callback',"SocialLoginController@handleProviderCallback")->name("social.handleProviderCallback");
    });

});