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

    // Public api routes
    Route::get('ical/{userId}/{icalId}', 'UserController@iCal');
    
    // Protected api routes
    Route::middleware('auth:api')->group(function () {

        // Client
        Route::get('client/show/{id}', 'ClientController@show');      
        Route::post('client/store', 'ClientController@store'); 
        Route::put('client/update/{id}', 'ClientController@update');      

        // User
        Route::get('me', 'UserController@me');
        Route::get('user', 'UserController@index');             
        Route::get('user/show/{id}', 'UserController@show');        
        Route::post('user/store', 'UserController@store'); 
        Route::put('user/update/{id}', 'UserController@update');        

        // Role
        Route::get('role', 'RoleController@index');
        Route::get('role/show/{id}', 'RoleController@show');
        Route::post('role/store', 'RoleController@store');
        Route::put('role/update/{id}', 'RoleController@update');
        Route::delete('role/delete/{id}', 'RoleController@delete');

        // Job
        Route::get('job', 'JobController@index');
        Route::get('job/show/{id}', 'JobController@show');
        Route::post('job/store', 'JobController@store');
        Route::put('job/update/{id}', 'JobController@update');

        // Schedule
        Route::get('schedule', 'ScheduleController@index');
        Route::get('schedule/show/{id}', 'ScheduleController@show');
        Route::post('schedule/store', 'ScheduleController@store');
        Route::put('schedule/update/{id}', 'ScheduleController@update');
        Route::delete('schedule/delete/{id}', 'ScheduleController@delete');

        // Timesheet
        Route::get('timesheet', 'TimesheetController@index');
        Route::get('timesheet/show/{id}', 'TimesheetController@show');
        Route::post('timesheet/store', 'TimesheetController@store');
        Route::put('timesheet/update/{id}', 'TimesheetController@update');
        Route::put('timesheet/approve/{id}', 'TimesheetController@approve');
        Route::put('timesheet/reject/{id}', 'TimesheetController@reject');
        Route::delete('timesheet/delete/{id}', 'TimesheetController@delete');

        // Team
        Route::get('team', 'TeamController@index');
        Route::get('team/show/{id}', 'TeamController@show');
        Route::post('team/store', 'TeamController@store');
        Route::put('team/update/{id}', 'TeamController@update');
        Route::delete('team/delete/{id}', 'TeamController@delete');

        // Activity
        Route::get('activity', 'ActivityController@index');
        Route::get('activity/show/{id}', 'ActivityController@show');
        Route::post('activity/store', 'ActivityController@store');
        Route::put('activity/update/{id}', 'ActivityController@update');

        // Form
        Route::get('form', 'FormController@index');
        Route::get('form/show/{id}', 'FormController@show');
        Route::post('form/store', 'FormController@store');
        Route::put('form/update/{id}', 'FormController@update');
        Route::delete('form/delete/{id}', 'FormController@delete');

        // Contact
        Route::get('contact', 'ContactController@index');
        Route::get('contact/show/{id}', 'ContactController@show');
        Route::post('contact/store', 'ContactController@store');
        Route::put('contact/update/{id}', 'ContactController@update');
        Route::delete('contact/delete/{id}', 'ContactController@delete');

        // Request
        Route::get('request', 'RequestController@index');
        Route::get('request/show/{id}', 'RequestController@show');
        Route::post('request/store', 'RequestController@store');
        Route::put('request/update/{id}', 'RequestController@update');
        Route::put('request/approve/{id}', 'RequestController@approve');
        Route::put('request/reject/{id}', 'RequestController@reject');
        Route::get('request/attachment/{reqId}/{fileId}', 'RequestController@attachment');

        // Comment
        Route::get('comment', 'CommentController@index');
        Route::post('comment/store', 'CommentController@store');
        Route::put('comment/update/{id}', 'CommentController@update');
        Route::delete('comment/delete/{id}', 'CommentController@delete');

        // webhooks
        Route::get('hooks', 'WebhookController@index');
        Route::post('hooks', 'WebhookController@subscribe');
        Route::delete('hooks/{id}', 'WebhookController@delete');
        Route::get('polling/trigger', 'WebhookController@pollForTrigger');
        
        // QuickBooks Auth
        Route::get('quickbooks/token/{code}/{realmid?}', 'QBsAuthController@token');
        Route::get('quickbooks/refresh', 'QBsAuthController@refresh');
        Route::get('quickbooks/hasvalidtoken', 'QBsAuthController@hasValidToken');

        // QuickBooks API
        Route::get('quickbooks/companyinfo', 'QuickBooksController@companyInfo');
        Route::get('quickbooks/employee', 'QuickBooksController@employeeList'); 
        Route::get('quickbooks/customer', 'QuickBooksController@customerList'); 
        Route::get('quickbooks/class', 'QuickBooksController@classList'); 
        Route::get('quickbooks/department', 'QuickBooksController@departmentList');         
    });

});
