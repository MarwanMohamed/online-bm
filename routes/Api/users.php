<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Feature;

Route::group(['namespace' => 'Users'], function () {
    Route::get('users/me','UserProfileController@me')->name('me.index');
    Route::post('users/change-requests/{changeRequest}','UserProfileController@resendEmailChangeRequest')->name('me.changeRequest.resend');
    Route::post('users/change-requests-cancel','UserProfileController@cancelEmailChangeRequests')->name('me.changeRequest.cancel');

    Route::put('users/me','UserProfileController@update')
        ->name('me.update')
        ->middleware("feature-state:".Feature::UPDATE_PROFILE().",1");


    Route::middleware('password-set')->group(function () {
        Route::get('users/list','UsersController@listAll')->name('users.list');
        Route::patch('users/{user}/active','UsersController@toggleActive')
            ->name('users.active')
            ->middleware("feature-state:".Feature::TOGGLE_ACTIVE().",1");

        Route::apiResource('users','UsersController')
            ->middleware("feature-state:".Feature::USER_CRUD_OPERATIONS().",1");
    });
});
