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
        
    #region Method: GET
    // Users
    Route::get('user/list', 'UserController@get');
    Route::get('user/client/{id}', 'UserController@client');
    
    // Jobs
    Route::get('jobs/list', 'JobController@list');
    Route::get('jobs/view/{id}', 'JobController@view');
    #endregion

    #region Method: POST
    // Auth
    Route::post('/login', 'AuthController@login');
    Route::post('/refresh', 'AuthController@refresh');

    // Jobs    
    Route::post('jobs/create', 'JobController@create');
    Route::post('jobs/update/{id}', 'JobController@update');    
    #endregion


    /**
     * Protected api routes
     */
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', 'AuthController@logout');
    });
    
});


