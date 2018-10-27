<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => '', 'namespace' => 'App\Ticsol\Components\Controllers\API'], function () {

    // Auth
    Route::post('/login', 'AuthController@login');
    Route::post('/refresh', 'AuthController@refresh');

    /**
     * Protected api routes
     */
    Route::middleware('auth:api')->group(function () {

        // Auth
        Route::post('/logout', 'AuthController@logout');

        // User
        Route::get('user', 'UserController@index');
        Route::get('user/info', 'UserController@current');
        Route::get('user/show/{id}', 'UserController@show');    
        Route::post('user/update/{id}', 'UserController@update');

        // Job
        Route::get('job', 'JobController@index');
        Route::get('job/show/{id}', 'JobController@show');
        Route::post('job/store', 'JobController@store');
        Route::post('job/update/{id}', 'JobController@update');

        // Schedule
        Route::get('schedule', 'ScheduleController@index');
        Route::get('schedule/show/{id}', 'ScheduleController@show');
        Route::post('schedule/store', 'ScheduleController@store');
        Route::post('schedule/update/{id}', 'ScheduleController@update');
        Route::post('schedule/delete/{id}', 'ScheduleController@delete');

        // Activity
        Route::get('activity', 'ActivityController@index');
        Route::get('activity/show/{id}', 'ActivityController@show');
        Route::post('activity/store', 'ActivityController@store');
        Route::post('activity/update/{id}', 'ActivityController@update');

        // Form
        Route::get('form', 'FormController@index');
        Route::get('form/show/{id}', 'FormController@show');
        Route::post('form/store', 'FormController@store');
        Route::post('form/update/{id}', 'FormController@update');

        // Contact
        Route::get('contact', 'ContactController@index');
        Route::get('contact/show/{id}', 'ContactController@show');
        Route::post('contact/store', 'ContactController@store');
        Route::post('contact/update/{id}', 'ContactController@update');

        // Request
        Route::get('request', 'RequestController@index');
        Route::get('request/show/{id}', 'RequestController@show');
        Route::post('request/store', 'RequestController@store');
        Route::post('request/update/{id}', 'RequestController@update');
    });

});
