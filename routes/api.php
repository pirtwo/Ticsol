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

Route::group([ 'prefix' => '', 'namespace' => 'App\Ticsol\Components\Controllers\API'], function(){
            
    // Auth
    Route::post('/login', 'AuthController@login');
    Route::post('/refresh', 'AuthController@refresh');

    // Users
    Route::get('user', 'UserController@index');
        
    // Jobs
    Route::get('job', 'JobController@index');
    Route::get('job/show/{id}', 'JobController@show'); 
    Route::post('job/store', 'JobController@store');
    Route::post('job/update/{id}', 'JobController@update');   
    
    // Schedule
    Route::get('schedule', 'ScheduleController@index'); 
    Route::get('schedule/show/{id}', 'ScheduleController@show'); 
    Route::post('schedule/store', 'ScheduleController@store');  
    Route::post('schedule/update/{id}', 'ScheduleController@update');  
    
    // Activity
    Route::get('activity', 'ActivityController@index');
    Route::get('activity/show/{id}', 'ActivityController@show');
    Route::post('activity/store', 'ActivityController@store');
    Route::post('activity/update/{id}', 'ActivityController@update');
    
    /**
     * Protected api routes
     */
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', 'AuthController@logout');
    });
    
});


