<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Feature;

Route::group(['namespace' => 'Features', 'middleware' => "feature-state:" . Feature::FEATURES() . ",1"], function () {
    Route::get('features/list', 'FeaturesController@listAll')->name('features.list');
});