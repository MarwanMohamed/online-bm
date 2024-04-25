<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Feature;

Route::group(['namespace' => 'Lookups', 'middleware' => "feature-state:" . Feature::LOOKUPS() . ",1"], function () {
    Route::get('lookups/list', 'LookupsController@listAll')->name('lookups.list');
    Route::apiResource('lookups', 'LookupsController')->except(['destroy']);
});

Route::group(['namespace' => 'Lookups', 'middleware' => "feature-state:" . Feature::LOOKUP_CATEGORIES() . ",1"], function () {
    Route::get('lookup-categories', 'LookupsController@listCategories')
        ->name('lookup-categories.list');
});
