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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([ 'prefix' => '', 'namespace' => 'App\Ticsol\Components\Controllers\API'], function(){
    
    // Route: Post
    Route::post('/login', 'AuthController@login');
    Route::post('/refresh', 'AuthController@refresh');
    

    // Route: Get
    
    Route::get('user/list', 'UserController@get');
    Route::get('user/client/{id}', 'UserController@client');
    
    Route::get('jobs/list', 'JobController@get');
    

    /**
     * Protected api routes
     */
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', 'AuthController@logout');
    });
    
});


