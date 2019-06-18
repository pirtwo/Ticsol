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

        // Role
        Route::get('role', 'RoleController@index');
        Route::get('role/show/{id}', 'RoleController@show');
        Route::post('role/store', 'RoleController@store');
        Route::post('role/update/{id}', 'RoleController@update');
        Route::post('role/delete/{id}', 'RoleController@delete');

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

        // Timesheet
        Route::get('timesheet', 'TimesheetController@index');
        Route::get('timesheet/show/{id}', 'TimesheetController@show');
        Route::post('timesheet/store', 'TimesheetController@store');
        Route::post('timesheet/update/{id}', 'TimesheetController@update');
        Route::post('timesheet/delete/{id}', 'TimesheetController@delete');

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

        // Comment
        Route::get('comment', 'CommentController@index');        
        Route::post('comment/store', 'CommentController@store');
        Route::post('comment/update/{id}', 'CommentController@update');        
        Route::post('comment/delete/{id}', 'CommentController@delete');
    });

});
