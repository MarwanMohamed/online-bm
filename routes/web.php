<?php
use App\Enums\Feature;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'GeneralController@welcome');

Route::group(['prefix' => 'api/auth', 'namespace' => 'Api\Auth'], function ()
{	
    Route::post('login', 'LoginController@login')->middleware("feature-state:".Feature::LOGIN().",1");;

    Route::group(['middleware' => 'auth:sanctum'], function ()
    {
        Route::post('logout', 'LoginController@logout');        
    });

});