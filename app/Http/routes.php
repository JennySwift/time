<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', 'HomeController@index');

// API
Route::group(['middleware' => ['auth', 'owner'], 'namespace' => 'API', 'prefix' => 'api'], function () {
    Route::get('timers/checkForTimerInProgress', 'TimersController@checkForTimerInProgress');
    Route::get('activities/getTotalMinutesForDay', 'ActivitiesController@calculateTotalMinutesForAllActivitiesForDay');
//    Route::get('activities/getTotalMinutesForWeek', 'ActivitiesController@calculateTotalMinutesForWeek');
    Route::resource('timers', 'TimersController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('activities', 'ActivitiesController', ['only' => ['index', 'store', 'update', 'destroy']]);
});
