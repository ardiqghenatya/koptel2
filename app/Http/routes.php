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

Route::get('/', 'HomeController@index');

Route::controllers(['auth' => 'Auth\AuthController', 'password' => 'Auth\PasswordController', ]);

Route::post('api/v1/oauth/login', 'API\LoginAPIController@login');

// Route::post('api/v1/oauth/login', function() {
//     return Response::json(Authorizer::issueAccessToken());
// });

Route::resource('api/v1/users', 'API\UserAPIController');

Route::get('api/v1/me', 'API\UserAPIController@me');

Route::resource('api/v1/permissions', 'API\PermissionAPIController');

Route::post('api/v1/permissionRoles/bulkUpdate', 'API\PermissionRoleAPIController@bulkUpdate');
Route::resource('api/v1/permissionRoles', 'API\PermissionRoleAPIController');

Route::resource('api/v1/permissionUsers', 'API\PermissionUserAPIController');

Route::resource('api/v1/roles', 'API\RoleAPIController');

Route::resource('api/v1/roleUsers', 'API\RoleUserAPIController');

Route::resource('api/v1/users', 'API\UserAPIController');

/**
 * Activity Logs
 */
Route::get('api/v1/activityLogs', 'API\ActivityLogAPIController@index');
Route::resource('activityLogs', 'ActivityLogController');

Route::get('activityLogs/{id}/delete', ['as' => 'activityLogs.delete', 'uses' => 'ActivityLogController@destroy', ]);

/**
 * Lemari
 */
Route::resource('api/v1/shelves', 'API\ShelfAPIController');

/**
 * Penyimpanan
 */
Route::post('api/v1/barcodeProcesses/take/{id}', ['as' => 'barcodeProcesses.take', 'uses' => 'API\BarcodeProcessAPIController@take']);
Route::get('api/v1/barcodeProcesses/statistic', ['as' => 'barcodeProcesses.statistic', 'uses' => 'API\BarcodeProcessAPIController@statistic']);
Route::resource('api/v1/barcodeProcesses', 'API\BarcodeProcessAPIController');
