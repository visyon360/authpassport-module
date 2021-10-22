<?php

use Illuminate\Http\Request;
use Modules\AuthPassport\Http\Controllers\AuthController;

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

Route::post('register', [
    'uses' => '\Modules\AuthPassport\Http\Controllers\RegisterController@register',
    'as'   => 'api.passport.register',
]);

Route::get('confirm-register', [
    'uses' => '\Modules\AuthPassport\Http\Controllers\RegisterController@confirmRegister',
    'as'   => 'api.passport.confirm',
]);

Route::post('login', [
    'uses' => '\Modules\AuthPassport\Http\Controllers\AuthController@login',
    'as'   => 'api.passport.login',
]);

Route::post('forgot-password', [
    'uses' => '\Modules\AuthPassport\Http\Controllers\PasswordController@forgotPassword',
    'as'   => 'api.passport.forgot-password',
]);

Route::post('reset-password', [
    'uses' => '\Modules\AuthPassport\Http\Controllers\PasswordController@resetPassword',
    'as'   => 'api.passport.reset-password',
]);

//add this middleware to ensure that every request is authenticated
Route::middleware('auth:api')->group(function () {
    Route::get('user', [
        'uses' => '\Modules\AuthPassport\Http\Controllers\AuthController@getUser',
        'as'   => 'api.passport.user',
    ]);

    Route::get('logout', [
        'uses' => '\Modules\AuthPassport\Http\Controllers\AuthController@logout',
        'as'   => 'api.passport.logout',
    ]);
});
