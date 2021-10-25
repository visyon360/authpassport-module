<?php

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

use Modules\AuthPassport\Http\Controllers\API\ForgotPasswordApiController;
use Modules\AuthPassport\Http\Controllers\API\LoginApiController;
use Modules\AuthPassport\Http\Controllers\API\LogoutApiController;
use Modules\AuthPassport\Http\Controllers\API\RegisterApiController;

Route::prefix('oauth')->name('oauth.')->group(
    function () {
        Route::post('login', [LoginApiController::class, '__invoke'])
             ->middleware('guest')->name('login');

        Route::post('logout', [LogoutApiController::class, '__invoke'])
             ->middleware('auth:api')->name('logout');

        Route::post('register', [RegisterApiController::class, '__invoke'])
            ->middleware('guest')->name('register');

        Route::post('forgot-password', [ForgotPasswordApiController::class, '__invoke'])
             ->name('password.email');
        //
        //        Route::post('reset-password', [NewPasswordController::class, '__invoke'])
        //            ->name('password.update');
    }
);
