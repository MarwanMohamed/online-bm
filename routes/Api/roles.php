<?php

use App\Enums\Feature;

Route::group([
    'namespace' => '\App\Http\Controllers\Api\Role',
    "as" => "roles.",
    "prefix" => "roles",
    'middleware' => "feature-state:" . Feature::ROLES() . ",1"
    ], function () {
    Route::get('', 'RoleController@index')->name('datatable');
    Route::get('/datatable', 'RoleController@index')->name('datatable');
    Route::get('/list', 'RoleController@listAll')->name('list');
    Route::get('{role}', 'RoleController@list')->name('list');
    Route::post('', 'RoleController@store')->name('add');
    Route::put('{role}', 'RoleController@update')->name('update');
    Route::delete('{role}', 'RoleController@destroy')->name('delete');
});
