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


Route::post('/login', 'api\auth\AuthController@login');
Route::post('/refresh', 'api\auth\AuthController@refresh');

/**
 * Protected api routes
 */
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'api\auth\AuthController@logout');
});
