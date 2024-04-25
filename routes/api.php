<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Enums\Feature;

require 'Api/auth.php';
require 'Api/features.php';

Route::group(['middleware' => ['auth:sanctum']], function () {

    foreach (glob(__DIR__.'/Api/*.php') as $route) {
        if (! Str::endsWith($route, ['Api/auth.php','Api/features.php'])) {
            require $route;
        }
    }

    Route::get('roles', 'GeneralController@getRoles')->middleware("feature-state:".Feature::ROLES().",1");;;
});
