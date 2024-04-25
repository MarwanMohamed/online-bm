<?php

use App\Enums\Feature;

Route::group(['namespace' => '\App\Http\Controllers\Api\Permissions', "prefix"=>"permissions", 'middleware' => "feature-state:" . Feature::PERMISSIONS() . ",1"], function () {
    Route::get('','PermissionController@list')->name('permissions.index');
    Route::get('/list','PermissionController@list')->name('permissions.list');
});
